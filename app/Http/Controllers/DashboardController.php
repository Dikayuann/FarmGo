<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // 1. Total Ternak milik user ini
        $totalTernak = Animal::where('user_id', $user->id)->count();

        // 2. Ternak yang perlu cek kesehatan (status sakit atau peringatan)
        $perluCekKesehatan = Animal::where('user_id', $user->id)
            ->whereIn('status_kesehatan', ['Sakit', 'Peringatan'])
            ->count();

        // 3. Status Peternakan (berdasarkan persentase ternak sehat)
        $ternakSehat = Animal::where('user_id', $user->id)
            ->where('status_kesehatan', 'Sehat')
            ->count();

        $persenSehat = $totalTernak > 0 ? ($ternakSehat / $totalTernak) * 100 : 0;

        if ($totalTernak === 0) {
            $statusPeternakan = '-';
        } elseif ($persenSehat >= 80) {
            $statusPeternakan = 'Baik';
        } elseif ($persenSehat >= 50) {
            $statusPeternakan = 'Sedang';
        } else {
            $statusPeternakan = 'Perlu Perhatian';
        }

        // 4. Data Populasi untuk Chart (6 bulan terakhir)
        $populationData = $this->getPopulationData($user->id);
        $monthLabels = $this->getMonthLabels();

        // 5. Tugas Kesehatan Mendatang (health records dengan pemeriksaan_berikutnya di masa depan)
        $tugasKesehatan = HealthRecord::whereHas('animal', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereNotNull('pemeriksaan_berikutnya')
            ->where('pemeriksaan_berikutnya', '>=', Carbon::today())
            ->orderBy('pemeriksaan_berikutnya', 'asc')
            ->limit(5)
            ->with('animal') // Eager loading untuk menghindari N+1 query
            ->get();

        // 6. Reproduksi Mendatang (untuk nanti, set 0 dulu)
        $reproduksiMendatang = 0;

        // 7. Data Reproduksi untuk Chart (untuk nanti, set array kosong dulu)
        $reproductionData = [];

        // Kirim semua data ke view
        return view('dashboard', compact(
            'totalTernak',
            'perluCekKesehatan',
            'statusPeternakan',
            'populationData',
            'monthLabels',
            'reproductionData',
            'tugasKesehatan',
            'reproduksiMendatang'
        ));
    }

    /**
     * Ambil data populasi 6 bulan terakhir
     */
    private function getPopulationData($userId)
    {
        $data = [];

        // Loop 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i)->endOfMonth();

            // Hitung jumlah ternak yang created_at <= akhir bulan tersebut
            // Menggunakan created_at lebih akurat daripada tanggal_lahir
            $count = Animal::where('user_id', $userId)
                ->where('created_at', '<=', $date)
                ->count();

            $data[] = $count;
        }

        return $data;
    }

    /**
     * Generate label bulan untuk 6 bulan terakhir
     */
    private function getMonthLabels()
    {
        $labels = [];

        // Loop 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            // Format: Jan, Feb, Mar, dst
            $labels[] = $date->translatedFormat('M');
        }

        return $labels;
    }
}