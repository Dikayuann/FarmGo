<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaksinasi;
use App\Models\Animal;
use App\Http\Requests\StoreVaksinasiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class VaksinasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $animalId = $request->input('animal_id', 'all');
        $jenisVaksin = $request->input('jenis_vaksin', 'all');
        $userId = Auth::id();

        // Get vaccinations for user's animals only with eager loading
        $vaksinasis = Vaksinasi::whereHas('animal', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->with('animal:id,nama_hewan,kode_hewan,user_id')
            ->search($search)
            ->byAnimal($animalId)
            ->byJenisVaksin($jenisVaksin)
            ->orderBy('tanggal_vaksin', 'desc')
            ->paginate(10);

        // Cache animals list for dropdown for 5 minutes
        $animals = Cache::remember("user_animals_list_{$userId}", 300, function () use ($userId) {
            return Animal::where('user_id', $userId)
                ->orderBy('nama_hewan')
                ->get(['id', 'nama_hewan', 'kode_hewan']);
        });

        // Calculate stats
        $cacheKey = "vaksinasi_stats_user_{$userId}";

        if ($search || $animalId !== 'all' || $jenisVaksin !== 'all') {
            // Don't use cache if filters are active
            $stats = $this->calculateVaksinasiStats($userId);
        } else {
            $stats = Cache::remember($cacheKey, 120, function () use ($userId) {
                return $this->calculateVaksinasiStats($userId);
            });
        }

        return view('vaksinasi.index', compact('vaksinasis', 'animals', 'stats', 'search', 'animalId', 'jenisVaksin'));
    }

    /**
     * Calculate vaccination stats - extracted for caching
     */
    private function calculateVaksinasiStats($userId)
    {
        $statsData = Vaksinasi::whereHas('animal', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN MONTH(tanggal_vaksin) = ? AND YEAR(tanggal_vaksin) = ? THEN 1 ELSE 0 END) as bulan_ini,
                SUM(CASE WHEN jadwal_berikutnya IS NOT NULL AND jadwal_berikutnya > CURDATE() THEN 1 ELSE 0 END) as mendatang
            ', [now()->month, now()->year])
            ->first();

        // Get most common vaccine type
        $topVaksin = Vaksinasi::whereHas('animal', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->selectRaw('jenis_vaksin, COUNT(*) as count')
            ->groupBy('jenis_vaksin')
            ->orderBy('count', 'desc')
            ->first();

        return [
            'total' => $statsData->total ?? 0,
            'bulan_ini' => $statsData->bulan_ini ?? 0,
            'mendatang' => $statsData->mendatang ?? 0,
            'top_vaksin' => $topVaksin->jenis_vaksin ?? '-',
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get user's animals for dropdown
        $animals = Animal::where('user_id', Auth::id())
            ->orderBy('nama_hewan')
            ->get();

        $animalId = $request->query('animal_id');
        return view('vaksinasi.create', compact('animals', 'animalId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVaksinasiRequest $request)
    {
        // Verify animal belongs to user
        $animal = Animal::where('id', $request->animal_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $data = $request->validated();

        $vaksinasi = Vaksinasi::create($data);

        // Create notification if next vaccination is scheduled
        if (!empty($data['jadwal_berikutnya'])) {
            $jadwalDate = \Carbon\Carbon::parse($data['jadwal_berikutnya']);

            \App\Models\Notifikasi::create([
                'user_id' => Auth::id(),
                'animal_id' => $animal->id,
                'perkawinan_id' => null,
                'jenis_notifikasi' => 'vaksinasi_berikutnya',
                'pesan' => "💉 Vaksinasi berikutnya untuk {$animal->kode_hewan} - {$animal->nama_hewan} telah dijadwalkan pada {$jadwalDate->format('d/m/Y')}. " .
                    "Jenis vaksin: {$data['jenis_vaksin']}. " .
                    "Anda akan menerima pengingat menjelang tanggal vaksinasi.",
                'tanggal_kirim' => now(),
                'status' => 'belum_dibaca',
            ]);
        }

        // Clear cache
        Cache::forget("vaksinasi_stats_user_" . Auth::id());

        return redirect()->route('vaksinasi.index')
            ->with('success', 'Data vaksinasi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vaksinasi = Vaksinasi::whereHas('animal', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('animal')->findOrFail($id);

        return view('vaksinasi.show', compact('vaksinasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vaksinasi = Vaksinasi::whereHas('animal', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('animal')->findOrFail($id);

        // Get user's animals for dropdown
        $animals = Animal::where('user_id', Auth::id())
            ->orderBy('nama_hewan')
            ->get();

        return view('vaksinasi.edit', compact('vaksinasi', 'animals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreVaksinasiRequest $request, string $id)
    {
        $vaksinasi = Vaksinasi::whereHas('animal', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        // Verify animal belongs to user
        $animal = Animal::where('id', $request->animal_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if jadwal_berikutnya changed or was added
        $oldJadwal = $vaksinasi->jadwal_berikutnya;
        $newJadwal = $request->jadwal_berikutnya;

        $vaksinasi->update($request->validated());

        // Create notification if jadwal was added or changed
        if ($newJadwal && $oldJadwal != $newJadwal) {
            $jadwalDate = \Carbon\Carbon::parse($newJadwal);
            $action = $oldJadwal ? 'diubah' : 'dijadwalkan';

            \App\Models\Notifikasi::create([
                'user_id' => Auth::id(),
                'animal_id' => $animal->id,
                'perkawinan_id' => null,
                'jenis_notifikasi' => 'vaksinasi_berikutnya',
                'pesan' => "💉 Vaksinasi berikutnya untuk {$animal->kode_hewan} - {$animal->nama_hewan} telah {$action} menjadi {$jadwalDate->format('d/m/Y')}. " .
                    "Jenis vaksin: {$request->jenis_vaksin}. " .
                    "Anda akan menerima pengingat menjelang tanggal vaksinasi.",
                'tanggal_kirim' => now(),
                'status' => 'belum_dibaca',
            ]);
        }

        // Clear cache
        Cache::forget("vaksinasi_stats_user_" . Auth::id());

        return redirect()->route('vaksinasi.index')
            ->with('success', 'Data vaksinasi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vaksinasi = Vaksinasi::whereHas('animal', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        $vaksinasi->delete();

        return redirect()->route('vaksinasi.index')
            ->with('success', 'Data vaksinasi berhasil dihapus!');
    }
}
