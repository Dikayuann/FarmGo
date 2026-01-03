<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\HealthRecord;
use App\Models\Perkawinan;
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

        // 2. Ternak Hasil Perkawinan
        $ternakPerkawinan = Animal::where('user_id', $user->id)
            ->where('status_ternak', 'perkawinan')
            ->count();

        // 3. Status Peternakan (berdasarkan total ternak dan reproduksi)
        if ($totalTernak === 0) {
            $statusPeternakan = '-';
        } elseif ($totalTernak >= 20) {
            $statusPeternakan = 'Berkembang';
        } elseif ($totalTernak >= 10) {
            $statusPeternakan = 'Baik';
        } else {
            $statusPeternakan = 'Mulai Berkembang';
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

        // 6. Reproduksi Mendatang (upcoming reminders in next 14 days)
        $reproduksiMendatang = Perkawinan::byUser($user->id)
            ->upcomingReminders(14)
            ->count();

        // 7. Data Reproduksi untuk Chart (success rate per month)
        $reproductionData = $this->getReproductionSuccessRate($user->id);

        // Kirim semua data ke view
        return view('dashboard', compact(
            'totalTernak',
            'ternakPerkawinan',
            'statusPeternakan',
            'populationData',
            'monthLabels',
            'reproductionData',
            'tugasKesehatan',
            'reproduksiMendatang'
        ));
    }

    /**
     * Get reproduction success rate for the last 5 months
     */
    private function getReproductionSuccessRate($userId)
    {
        $data = [];

        for ($i = 4; $i >= 0; $i--) {
            $startDate = Carbon::now()->subMonths($i)->startOfMonth();
            $endDate = Carbon::now()->subMonths($i)->endOfMonth();

            // Count total matings in this month
            $totalMatings = Perkawinan::byUser($userId)
                ->whereBetween('tanggal_perkawinan', [$startDate, $endDate])
                ->count();

            // Count successful births
            $successfulBirths = Perkawinan::byUser($userId)
                ->where('status_reproduksi', 'melahirkan')
                ->whereBetween('tanggal_perkawinan', [$startDate, $endDate])
                ->count();

            // Calculate success rate as percentage
            $successRate = $totalMatings > 0 ? round(($successfulBirths / $totalMatings) * 100) : 0;
            $data[] = $successRate;
        }

        return $data;
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