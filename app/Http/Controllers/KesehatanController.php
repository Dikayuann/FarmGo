<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthRecord;
use App\Models\Animal;
use App\Http\Requests\StoreHealthRecordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class KesehatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $animalId = $request->input('animal_id', 'all');
        $status = $request->input('status', 'all');
        $jenisPemeriksaan = $request->input('jenis_pemeriksaan', 'all');
        $userId = Auth::id();

        // Get health records for user's animals only with eager loading
        $healthRecords = HealthRecord::whereHas('animal', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->with('animal:id,nama_hewan,kode_hewan,user_id') // Only load needed fields
            ->search($search)
            ->byAnimal($animalId)
            ->byStatus($status)
            ->byJenisPemeriksaan($jenisPemeriksaan)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->paginate(10);

        // Cache animals list for dropdown for 5 minutes
        $animals = Cache::remember("user_animals_list_{$userId}", 300, function () use ($userId) {
            return Animal::where('user_id', $userId)
                ->orderBy('nama_hewan')
                ->get(['id', 'nama_hewan', 'kode_hewan']);
        });

        // Optimize stats with single query
        $cacheKey = "kesehatan_stats_user_{$userId}";

        if ($search || $animalId !== 'all' || $status !== 'all' || $jenisPemeriksaan !== 'all') {
            // Don't use cache if filters are active
            $stats = $this->calculateHealthStats($userId);
        } else {
            $stats = Cache::remember($cacheKey, 120, function () use ($userId) {
                return $this->calculateHealthStats($userId);
            });
        }

        return view('kesehatan.index', compact('healthRecords', 'animals', 'stats', 'search', 'animalId', 'status', 'jenisPemeriksaan'));
    }

    /**
     * Calculate health stats - extracted for caching
     */
    private function calculateHealthStats($userId)
    {
        // Single optimized query
        $statsData = HealthRecord::whereHas('animal', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status_kesehatan = "sehat" THEN 1 ELSE 0 END) as sehat,
                SUM(CASE WHEN status_kesehatan = "sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN MONTH(tanggal_pemeriksaan) = ? AND YEAR(tanggal_pemeriksaan) = ? THEN 1 ELSE 0 END) as bulan_ini
            ', [now()->month, now()->year])
            ->first();

        return [
            'total' => $statsData->total ?? 0,
            'sehat' => $statsData->sehat ?? 0,
            'sakit' => $statsData->sakit ?? 0,
            'bulan_ini' => $statsData->bulan_ini ?? 0,
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
        return view('kesehatan.create', compact('animals', 'animalId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHealthRecordRequest $request)
    {
        // Verify animal belongs to user
        $animal = Animal::where('id', $request->animal_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $data = $request->validated();
        $data['tanggal_pemeriksaan'] = \Carbon\Carbon::parse($request->tanggal_pemeriksaan)->format('Y-m-d H:i:s');

        $healthRecord = HealthRecord::create($data);

        // Create notification if animal is sick or in emergency
        if (in_array($data['status_kesehatan'], ['sakit', 'darurat'])) {
            $statusText = $data['status_kesehatan'] === 'darurat' ? 'DARURAT' : 'Sakit';
            $emoji = $data['status_kesehatan'] === 'darurat' ? '🚨' : '⚠️';

            \App\Models\Notifikasi::create([
                'user_id' => Auth::id(),
                'animal_id' => $animal->id,
                'perkawinan_id' => null,
                'jenis_notifikasi' => 'kesehatan_darurat',
                'pesan' => "{$emoji} {$statusText}! {$animal->kode_hewan} - {$animal->nama_hewan} memerlukan perhatian. Status: {$statusText}. " .
                    ($data['diagnosis'] ? "Diagnosis: {$data['diagnosis']}. " : "") .
                    "Segera lakukan tindakan yang diperlukan.",
                'tanggal_kirim' => now(),
                'status' => 'belum_dibaca',
            ]);
        }

        return redirect()->route('kesehatan.index')
            ->with('success', 'Catatan kesehatan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $healthRecord = HealthRecord::whereHas('animal', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('animal')->findOrFail($id);

        return view('kesehatan.show', compact('healthRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $healthRecord = HealthRecord::whereHas('animal', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('animal')->findOrFail($id);

        // Get user's animals for dropdown
        $animals = Animal::where('user_id', Auth::id())
            ->orderBy('nama_hewan')
            ->get();

        return view('kesehatan.edit', compact('healthRecord', 'animals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreHealthRecordRequest $request, string $id)
    {
        $healthRecord = HealthRecord::whereHas('animal', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        // Verify animal belongs to user
        $animal = Animal::where('id', $request->animal_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if status changed from healthy to sick/emergency
        $oldStatus = $healthRecord->status_kesehatan;
        $newStatus = $request->status_kesehatan;

        $healthRecord->update($request->validated());

        // Create notification only if status worsened (healthy -> sick/emergency)
        if ($oldStatus === 'sehat' && in_array($newStatus, ['sakit', 'darurat'])) {
            $statusText = $newStatus === 'darurat' ? 'DARURAT' : 'Sakit';
            $emoji = $newStatus === 'darurat' ? '🚨' : '⚠️';

            \App\Models\Notifikasi::create([
                'user_id' => Auth::id(),
                'animal_id' => $animal->id,
                'perkawinan_id' => null,
                'jenis_notifikasi' => 'kesehatan_darurat',
                'pesan' => "{$emoji} Perubahan Status! {$animal->kode_hewan} - {$animal->nama_hewan} sekarang berstatus {$statusText}. " .
                    ($request->diagnosis ? "Diagnosis: {$request->diagnosis}. " : "") .
                    "Segera lakukan tindakan yang diperlukan.",
                'tanggal_kirim' => now(),
                'status' => 'belum_dibaca',
            ]);
        }

        return redirect()->route('kesehatan.index')
            ->with('success', 'Catatan kesehatan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $healthRecord = HealthRecord::whereHas('animal', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        $healthRecord->delete();

        return redirect()->route('kesehatan.index')
            ->with('success', 'Catatan kesehatan berhasil dihapus!');
    }
}
