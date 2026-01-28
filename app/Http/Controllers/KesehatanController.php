<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthRecord;
use App\Models\Animal;
use App\Models\CalendarEvent;
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

        // Get weight history if animal is pre-selected
        $weightHistory = null;
        $currentWeight = null;

        if ($animalId) {
            $animal = Animal::find($animalId);
            $currentWeight = $animal?->berat_badan;

            // Get last 5 weight records
            $weightHistory = HealthRecord::where('animal_id', $animalId)
                ->whereNotNull('berat_badan')
                ->orderBy('tanggal_pemeriksaan', 'desc')
                ->limit(5)
                ->get(['berat_badan', 'tanggal_pemeriksaan']);
        }

        return view('kesehatan.create', compact('animals', 'animalId', 'currentWeight', 'weightHistory'));
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

        // Prevent duplicate submissions (check for identical record within last 5 seconds)
        $recentDuplicate = HealthRecord::where('animal_id', $request->animal_id)
            ->where('tanggal_pemeriksaan', $data['tanggal_pemeriksaan'])
            ->where('jenis_pemeriksaan', $data['jenis_pemeriksaan'])
            ->where('created_at', '>=', now()->subSeconds(5))
            ->first();

        if ($recentDuplicate) {
            // Duplicate detected - redirect with success message (silent deduplication)
            return redirect()
                ->route('kesehatan.index')
                ->with('success', 'Catatan kesehatan berhasil ditambahkan!');
        }

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
                    ($data['tindakan'] ? "Tindakan: {$data['tindakan']}. " : "") .
                    "Segera lakukan tindakan yang diperlukan.",
                'tanggal_kirim' => now(),
                'status' => 'belum_dibaca',
            ]);
        }

        // Create notification if next checkup is scheduled
        if (!empty($data['pemeriksaan_berikutnya'])) {
            $checkupDate = \Carbon\Carbon::parse($data['pemeriksaan_berikutnya']);

            \App\Models\Notifikasi::create([
                'user_id' => Auth::id(),
                'animal_id' => $animal->id,
                'perkawinan_id' => null,
                'jenis_notifikasi' => 'pemeriksaan_berikutnya',
                'pesan' => "📅 Pemeriksaan berikutnya untuk {$animal->kode_hewan} - {$animal->nama_hewan} telah dijadwalkan pada {$checkupDate->format('d/m/Y')}. " .
                    "Anda akan menerima pengingat menjelang tanggal pemeriksaan.",
                'tanggal_kirim' => now(),
                'status' => 'belum_dibaca',
            ]);

            // Create calendar event for next checkup
            CalendarEvent::create([
                'user_id' => Auth::id(),
                'animal_id' => $animal->id,
                'event_type' => CalendarEvent::TYPE_HEALTH_CHECKUP,
                'title' => "Pemeriksaan Kesehatan - {$animal->nama_hewan}",
                'description' => "Jadwal pemeriksaan kesehatan untuk {$animal->kode_hewan} ({$animal->nama_hewan})." .
                    ($data['diagnosis'] ? " Diagnosis sebelumnya: {$data['diagnosis']}." : ""),
                'event_date' => $checkupDate,
                'completed' => false,
            ]);
        }

        // Create vaccination record if vaccination data is provided
        if (!empty($request->jenis_vaksin)) {
            \App\Models\Vaksinasi::create([
                'animal_id' => $animal->id,
                'tanggal_vaksin' => $request->tanggal_pemeriksaan ? \Carbon\Carbon::parse($request->tanggal_pemeriksaan)->format('Y-m-d') : now()->format('Y-m-d'),
                'jenis_vaksin' => $request->jenis_vaksin,
                'dosis' => $request->dosis_vaksin ?? '-',
                'rute_pemberian' => $request->rute_pemberian ?? 'oral',
                'masa_penarikan' => $request->masa_penarikan ?? 0,
                'nama_dokter' => $request->nama_dokter_vaksin ?? '-',
                'jadwal_berikutnya' => $request->jadwal_vaksin_berikutnya,
                'catatan' => $request->catatan_vaksin,
            ]);

            // Create notification if next vaccination is scheduled
            if (!empty($request->jadwal_vaksin_berikutnya)) {
                $jadwalDate = \Carbon\Carbon::parse($request->jadwal_vaksin_berikutnya);

                \App\Models\Notifikasi::create([
                    'user_id' => Auth::id(),
                    'animal_id' => $animal->id,
                    'perkawinan_id' => null,
                    'jenis_notifikasi' => 'vaksinasi_berikutnya',
                    'pesan' => "💉 Vaksinasi berikutnya untuk {$animal->kode_hewan} - {$animal->nama_hewan} telah dijadwalkan pada {$jadwalDate->format('d/m/Y')}. " .
                        "Jenis vaksin: {$request->jenis_vaksin}. " .
                        "Anda akan menerima pengingat menjelang tanggal vaksinasi.",
                    'tanggal_kirim' => now(),
                    'status' => 'belum_dibaca',
                ]);

                // Create calendar event for next vaccination
                CalendarEvent::create([
                    'user_id' => Auth::id(),
                    'animal_id' => $animal->id,
                    'event_type' => CalendarEvent::TYPE_VACCINATION,
                    'title' => "Vaksinasi - {$animal->nama_hewan}",
                    'description' => "Jadwal vaksinasi untuk {$animal->kode_hewan} ({$animal->nama_hewan}). " .
                        "Jenis vaksin: {$request->jenis_vaksin}.",
                    'event_date' => $jadwalDate,
                    'completed' => false,
                ]);
            }
        }

        // Clear cache
        Cache::forget("kesehatan_stats_user_" . Auth::id());
        Cache::forget("vaksinasi_stats_user_" . Auth::id());

        $message = 'Catatan kesehatan berhasil ditambahkan!';
        if (!empty($request->jenis_vaksin)) {
            $message .= ' Data vaksinasi juga telah tersimpan.';
        }

        // Update master weight in Animal table to reflect latest status
        $animal->update([
            'berat_badan' => $data['berat_badan']
        ]);

        // Update master weight in Animal table to reflect latest status
        $animal->update([
            'berat_badan' => $data['berat_badan']
        ]);

        return redirect()->route('kesehatan.index')
            ->with('success', $message)
            ->with('toast', true);
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

        // Get weight history (excluding current record)
        $weightHistory = HealthRecord::where('animal_id', $healthRecord->animal_id)
            ->where('id', '!=', $id)
            ->whereNotNull('berat_badan')
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->limit(5)
            ->get(['berat_badan', 'tanggal_pemeriksaan']);

        $currentWeight = $healthRecord->animal->berat_badan;

        return view('kesehatan.edit', compact('healthRecord', 'animals', 'currentWeight', 'weightHistory'));
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

        // Check if checkup date changed or was added
        $oldCheckupDate = $healthRecord->pemeriksaan_berikutnya;
        $newCheckupDate = $request->pemeriksaan_berikutnya;

        $healthRecord->update($request->validated());

        // Update master weight if this is the most recent record
        // Prevents updating master weight if user is editing an old record
        $latestRecord = HealthRecord::where('animal_id', $animal->id)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->first();

        if ($latestRecord && $latestRecord->id === $healthRecord->id) {
            $animal->update([
                'berat_badan' => $request->berat_badan
            ]);
        }

        // Update master weight if this is the most recent record
        // Prevents updating master weight if user is editing an old record
        $latestRecord = HealthRecord::where('animal_id', $animal->id)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->first();

        if ($latestRecord && $latestRecord->id === $healthRecord->id) {
            $animal->update([
                'berat_badan' => $request->berat_badan
            ]);
        }

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
                    ($request->tindakan ? "Tindakan: {$request->tindakan}. " : "") .
                    "Segera lakukan tindakan yang diperlukan.",
                'tanggal_kirim' => now(),
                'status' => 'belum_dibaca',
            ]);
        }

        // Create notification if checkup date was added or changed
        if ($newCheckupDate && $oldCheckupDate != $newCheckupDate) {
            $checkupDate = \Carbon\Carbon::parse($newCheckupDate);
            $action = $oldCheckupDate ? 'diubah' : 'dijadwalkan';

            \App\Models\Notifikasi::create([
                'user_id' => Auth::id(),
                'animal_id' => $animal->id,
                'perkawinan_id' => null,
                'jenis_notifikasi' => 'pemeriksaan_berikutnya',
                'pesan' => "📅 Pemeriksaan berikutnya untuk {$animal->kode_hewan} - {$animal->nama_hewan} telah {$action} menjadi {$checkupDate->format('d/m/Y')}. " .
                    "Anda akan menerima pengingat menjelang tanggal pemeriksaan.",
                'tanggal_kirim' => now(),
                'status' => 'belum_dibaca',
            ]);
        }

        // Clear cache
        Cache::forget("kesehatan_stats_user_" . Auth::id());

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

    /**
     * Get weight history for an animal (AJAX endpoint)
     */
    public function getWeightHistory($id)
    {
        $animal = Animal::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $weightHistory = HealthRecord::where('animal_id', $id)
            ->whereNotNull('berat_badan')
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->limit(5)
            ->get(['berat_badan', 'tanggal_pemeriksaan']);

        return response()->json([
            'current_weight' => $animal->berat_badan,
            'initial_weight' => $animal->berat_badan_awal ?? $animal->berat_badan,
            'history' => $weightHistory->map(function ($record) {
                return [
                    'weight' => number_format($record->berat_badan, 1),
                    'date' => \Carbon\Carbon::parse($record->tanggal_pemeriksaan)->format('d/m/Y')
                ];
            })
        ]);
    }
}
