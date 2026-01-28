<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Notifikasi;
use App\Http\Requests\StoreAnimalRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class TernakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $jenis = $request->input('jenis', 'all');
        $status = $request->input('status', 'all');
        $userId = Auth::id();

        $animals = Animal::where('user_id', $userId)
            ->withCount(['reproduksisAsBetina', 'reproduksisAsJantan', 'healthRecords'])
            ->search($search)
            ->byJenis($jenis)
            ->byStatus($status)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Cache stats for 2 minutes per user - only recalculate if no filters
        $cacheKey = "ternak_stats_user_{$userId}";

        if ($search || $jenis !== 'all' || $status !== 'all') {
            // Don't use cache if filters are active
            $stats = $this->calculateStats($userId);
        } else {
            $stats = Cache::remember($cacheKey, 120, function () use ($userId) {
                return $this->calculateStats($userId);
            });
        }

        // Check if print all QR is requested
        if ($request->has('print_all')) {
            $allAnimals = Animal::where('user_id', $userId)->get();
            return view('ternak.print-all-qr', compact('allAnimals'));
        }

        return view('ternak.index', compact('animals', 'stats', 'search', 'jenis', 'status'));
    }

    /**
     * Calculate stats for animals - extracted for caching
     */
    private function calculateStats($userId)
    {
        // Single optimized query to get all stats at once
        $statsData = Animal::where('user_id', $userId)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN jenis_hewan = "sapi" THEN 1 ELSE 0 END) as sapi,
                SUM(CASE WHEN jenis_hewan = "kambing" THEN 1 ELSE 0 END) as kambing,
                SUM(CASE WHEN jenis_hewan = "domba" THEN 1 ELSE 0 END) as domba,
                SUM(CASE WHEN status_ternak = "beli" THEN 1 ELSE 0 END) as beli
            ')
            ->first();

        return [
            'total' => $statsData->total ?? 0,
            'sapi' => $statsData->sapi ?? 0,
            'kambing' => $statsData->kambing ?? 0,
            'domba' => $statsData->domba ?? 0,
            'beli' => $statsData->beli ?? 0,
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnimalRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        // Check if user can add more animals (quota limit)
        if (!$user->canAddAnimal()) {
            return back()->withErrors([
                'error' => 'Anda telah mencapai batas maksimal 10 hewan untuk akun Trial. Upgrade ke Premium untuk menambah lebih banyak hewan!'
            ])->with('show_upgrade_modal', true);
        }

        $data['user_id'] = Auth::id();

        // Generate kode_hewan if not provided
        if (empty($data['kode_hewan'])) {
            // Set prefix based on jenis_hewan
            $prefixMap = [
                'sapi' => 'SA',
                'kambing' => 'K',
                'domba' => 'D',
            ];
            $prefix = $prefixMap[$data['jenis_hewan']] ?? 'X';
            $userId = Auth::id();

            // Find last animal code with same prefix and user_id
            // Format: PREFIX-USERID-SEQUENCE (e.g., SA-2-001)
            $lastAnimal = Animal::where('user_id', $userId)
                ->where('kode_hewan', 'like', "{$prefix}-{$userId}-%")
                ->orderByRaw('CAST(SUBSTRING_INDEX(kode_hewan, "-", -1) AS UNSIGNED) DESC')
                ->first();

            $number = 1;
            if ($lastAnimal) {
                // Extract number after last dash
                $parts = explode('-', $lastAnimal->kode_hewan);
                $number = (int) end($parts) + 1;
            }

            $data['kode_hewan'] = "{$prefix}-{$userId}-" . str_pad($number, 3, '0', STR_PAD_LEFT);
        }

        // Set initial weight (immutable baseline)
        if (isset($data['berat_badan'])) {
            $data['berat_badan_awal'] = $data['berat_badan'];
        }

        $animal = Animal::create($data);

        // Generate QR Code
        $this->generateQRCode($animal);

        // Check if user is approaching quota limit and send notification
        if (!$user->hasActivePremium()) {
            $animalCount = $user->animals()->count();

            // Send warning notification at 8/10 animals
            if ($animalCount == 8) {
                Notifikasi::create([
                    'user_id' => $user->id,
                    'animal_id' => $animal->id,
                    'jenis_notifikasi' => 'quota_warning',
                    'pesan' => '⚠️ Peringatan Kuota: Anda telah menggunakan 8 dari 10 kuota hewan Trial. Tersisa 2 slot lagi. Upgrade ke Premium untuk unlimited!',
                    'tanggal_kirim' => now(),
                    'status' => 'belum_dibaca',
                ]);
            }

            // Show warning message at 9/10
            if ($animalCount == 9) {
                session()->flash('warning', 'Peringatan: Anda hanya memiliki 1 slot tersisa! Upgrade ke Premium untuk menambah lebih banyak hewan.');
            }
        }

        return redirect()->route('ternak.index')
            ->with('success', 'Hewan ternak berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $animal = Animal::where('user_id', Auth::id())
            ->withCount(['reproduksisAsBetina', 'reproduksisAsJantan', 'healthRecords'])
            ->with([
                'healthRecords' => function ($query) {
                    $query->orderBy('tanggal_pemeriksaan', 'desc');
                },
                'perkawinan' => function ($query) {
                    $query->with(['jantan', 'betina']);
                }
            ])
            ->findOrFail($id);

        // Get health statistics
        $healthStats = [
            'total_checkups' => $animal->healthRecords->count(),
            'latest_weight' => $animal->healthRecords->first()?->berat_badan ?? $animal->berat_badan,
            'weight_change' => $this->calculateWeightChange($animal),
        ];

        // AI Health Assessment
        $aiAnalyzer = new \App\Services\AiHealthAnalyzer();
        $riskScore = $aiAnalyzer->calculateHealthRiskScore($animal);
        $recommendations = $aiAnalyzer->generateSmartRecommendations($animal);

        // Determine risk level
        if ($riskScore >= 80) {
            $riskLevel = 'Tinggi';
            $riskColor = 'red';
        } elseif ($riskScore >= 60) {
            $riskLevel = 'Sedang';
            $riskColor = 'orange';
        } else {
            $riskLevel = 'Rendah';
            $riskColor = 'green';
        }

        return view('ternak.show', compact('animal', 'healthStats', 'riskScore', 'riskLevel', 'riskColor', 'recommendations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAnimalRequest $request, string $id)
    {
        $animal = Animal::where('user_id', Auth::id())->findOrFail($id);

        $data = $request->validated();

        // Prevent modification of initial weight (immutable)
        unset($data['berat_badan_awal']);

        $animal->update($data);

        // Regenerate QR Code
        $this->generateQRCode($animal);

        return redirect()->route('ternak.index')
            ->with('success', 'Data hewan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $animal = Animal::where('user_id', Auth::id())->findOrFail($id);

        // Validation 1: Check if betina is pregnant
        if ($animal->jenis_kelamin === 'betina') {
            $activePregnancy = \App\Models\Perkawinan::where('betina_id', $animal->id)
                ->where('status_reproduksi', 'bunting')
                ->exists();

            if ($activePregnancy) {
                return back()->withErrors([
                    'error' => 'Tidak dapat menghapus ternak yang sedang bunting. Silakan ubah status reproduksi terlebih dahulu atau tunggu hingga melahirkan.'
                ]);
            }
        }

        // Validation 2: Check if animal has offspring
        // Get all perkawinan IDs where this animal is parent
        $perkawinanIds = \App\Models\Perkawinan::where(function ($query) use ($animal) {
            $query->where('jantan_id', $animal->id)
                ->orWhere('betina_id', $animal->id);
        })->pluck('id');

        // Count offspring from those perkawinans
        $offspringCount = Animal::whereIn('perkawinan_id', $perkawinanIds)->count();

        if ($offspringCount > 0) {
            return back()->withErrors([
                'error' => "Tidak dapat menghapus ternak yang memiliki {$offspringCount} anak. Anak-anak akan kehilangan informasi orang tua jika induk dihapus."
            ]);
        }

        // Validation 3: Check if animal is offspring (has parent breeding record)
        // This is allowed, but we need to handle it properly
        if ($animal->perkawinan_id) {
            // Clear the perkawinan_id reference before deleting
            $animal->perkawinan_id = null;
            $animal->save();
        }

        // Delete QR code file if exists
        if ($animal->qr_url) {
            $qrPath = public_path('storage/qrcodes/qr_' . $animal->kode_hewan . '.svg');
            if (file_exists($qrPath)) {
                unlink($qrPath);
            }
        }

        // Delete associated breeding records (only those without offspring)
        \App\Models\Perkawinan::where(function ($query) use ($animal) {
            $query->where('jantan_id', $animal->id)
                ->orWhere('betina_id', $animal->id);
        })->delete();

        // Delete associated health records
        $animal->healthRecords()->delete();

        // Delete associated notifications
        \App\Models\Notifikasi::where('animal_id', $animal->id)->delete();

        // Clear user-specific cache
        $userId = Auth::id();
        Cache::forget("ternak_stats_user_{$userId}");

        $animal->delete();

        return redirect()->route('ternak.index')
            ->with('success', 'Hewan ternak berhasil dihapus!');
    }

    /**
     * Calculate weight change between latest and previous record
     */
    private function calculateWeightChange(Animal $animal)
    {
        $records = $animal->healthRecords->sortByDesc('tanggal_pemeriksaan')->values();

        if ($records->count() >= 2) {
            $latest = $records->get(0)->berat_badan;
            $previous = $records->get(1)->berat_badan;
            return round($latest - $previous, 2);
        }

        if ($records->count() == 1) {
            $latest = $records->get(0)->berat_badan;
            // Use initial weight as baseline
            $initial = $animal->berat_badan_awal ?? $animal->berat_badan;
            return round($latest - $initial, 2);
        }

        return 0;
    }

    /**
     * Generate QR Code for animal
     */
    private function generateQRCode(Animal $animal)
    {
        // Create QR code directory if it doesn't exist
        $qrPath = public_path('storage/qrcodes');
        if (!file_exists($qrPath)) {
            mkdir($qrPath, 0755, true);
        }

        // Generate QR code URL - points to animal detail page
        $url = route('ternak.show', $animal->id);

        // Generate QR code image
        $fileName = 'qr_' . $animal->kode_hewan . '.svg';
        $filePath = $qrPath . '/' . $fileName;

        QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($url, $filePath);

        // Update animal with QR URL
        $animal->update([
            'qr_url' => asset('storage/qrcodes/' . $fileName)
        ]);
    }
}
