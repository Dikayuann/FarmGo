<?php

namespace App\Services;

use App\Models\Animal;
use App\Models\HealthRecord;
use Carbon\Carbon;

class AiHealthAnalyzer
{
    /**
     * Calculate health risk score for an animal (0-100)
     * Higher score = higher risk
     */
    public function calculateHealthRiskScore(Animal $animal): int
    {
        $score = 0;

        // 1. Days since last checkup (max 40 points)
        $lastCheckup = $animal->healthRecords()
            ->whereNotNull('pemeriksaan_berikutnya')
            ->latest('tanggal_pemeriksaan')
            ->first();

        if (!$lastCheckup) {
            $score += 40; // No checkup record = high risk
        } else {
            $daysSinceCheckup = Carbon::parse($lastCheckup->tanggal_pemeriksaan)->diffInDays(now());

            if ($daysSinceCheckup > 90) {
                $score += 40;
            } elseif ($daysSinceCheckup > 60) {
                $score += 25;
            } elseif ($daysSinceCheckup > 30) {
                $score += 15;
            } else {
                $score += 5;
            }
        }

        // 2. Overdue checkups (max 30 points)
        $overdueCheckups = $animal->healthRecords()
            ->whereNotNull('pemeriksaan_berikutnya')
            ->where('pemeriksaan_berikutnya', '<', now())
            ->count();

        if ($overdueCheckups >= 2) {
            $score += 30;
        } elseif ($overdueCheckups == 1) {
            $score += 10;
        }

        // 3. Disease history (max 20 points)
        $healthIssuesCount = $animal->healthRecords()
            ->where('status_kesehatan', '!=', 'sehat')
            ->count();

        if ($healthIssuesCount >= 3) {
            $score += 20;
        } elseif ($healthIssuesCount >= 1) {
            $score += 10;
        }

        // 4. Age factor (max 10 points)
        if ($animal->tanggal_lahir) {
            $ageInYears = Carbon::parse($animal->tanggal_lahir)->age;

            if ($ageInYears < 1) {
                $score += 5; // Young animals need more care
            } elseif ($ageInYears > 7) {
                $score += 10; // Senior animals higher risk
            }
        }

        // Cap at 100
        return min($score, 100);
    }

    /**
     * Generate smart recommendations for an animal
     */
    public function generateSmartRecommendations(Animal $animal): array
    {
        $recommendations = [];

        // Check overdue vaccinations/checkups
        $overdueCheckups = $animal->healthRecords()
            ->whereNotNull('pemeriksaan_berikutnya')
            ->where('pemeriksaan_berikutnya', '<', now())
            ->get();

        foreach ($overdueCheckups as $checkup) {
            $daysOverdue = Carbon::parse($checkup->pemeriksaan_berikutnya)->diffInDays(now());
            $recommendations[] = "Jadwalkan pemeriksaan {$checkup->jenis_pemeriksaan} (telat {$daysOverdue} hari)";
        }

        // Check if no recent checkup
        $lastCheckup = $animal->healthRecords()
            ->latest('tanggal_pemeriksaan')
            ->first();

        if (!$lastCheckup) {
            $recommendations[] = "Lakukan pemeriksaan kesehatan awal untuk baseline data";
        } else {
            $daysSince = Carbon::parse($lastCheckup->tanggal_pemeriksaan)->diffInDays(now());
            if ($daysSince > 90) {
                $recommendations[] = "Sudah {$daysSince} hari sejak pemeriksaan terakhir - disarankan checkup rutin";
            }
        }

        // Check disease history
        $recentIssues = $animal->healthRecords()
            ->where('status_kesehatan', '!=', 'sehat')
            ->where('tanggal_pemeriksaan', '>=', now()->subMonths(3))
            ->count();

        if ($recentIssues > 0) {
            $recommendations[] = "Monitor ketat - ada {$recentIssues} masalah kesehatan dalam 3 bulan terakhir";
        }

        // Breeding health check
        if ($animal->jenis_kelamin === 'betina') {
            $recentPregnancy = $animal->asBetina()
                ->where('status_reproduksi', 'bunting')
                ->latest()
                ->first();

            if ($recentPregnancy) {
                $recommendations[] = "Sedang bunting - pastikan nutrisi optimal dan checkup berkala";
            }
        }

        // If no issues found
        if (empty($recommendations)) {
            $recommendations[] = "Kondisi kesehatan baik - lanjutkan perawatan rutin";
        }

        return $recommendations;
    }

    /**
     * Analyze overall farm health
     */
    public function analyzeFarmHealth(int $userId): array
    {
        $animals = Animal::where('user_id', $userId)->get();

        if ($animals->isEmpty()) {
            return [
                'total_animals' => 0,
                'high_risk' => 0,
                'medium_risk' => 0,
                'low_risk' => 0,
                'actions' => ['Belum ada data ternak - tambahkan ternak untuk mulai monitoring']
            ];
        }

        $highRiskCount = 0;
        $mediumRiskCount = 0;
        $needsVaccination = 0;
        $needsCheckup = 0;

        foreach ($animals as $animal) {
            $score = $this->calculateHealthRiskScore($animal);

            if ($score >= 80) {
                $highRiskCount++;
            } elseif ($score >= 60) {
                $mediumRiskCount++;
            }

            // Count animals needing vaccination
            $overdue = $animal->healthRecords()
                ->whereNotNull('pemeriksaan_berikutnya')
                ->where('pemeriksaan_berikutnya', '<', now())
                ->count();

            if ($overdue > 0) {
                $needsVaccination++;
            }

            // Count animals needing checkup
            $lastCheckup = $animal->healthRecords()
                ->latest('tanggal_pemeriksaan')
                ->first();

            if (!$lastCheckup || Carbon::parse($lastCheckup->tanggal_pemeriksaan)->diffInDays(now()) > 90) {
                $needsCheckup++;
            }
        }

        $actions = [];

        if ($highRiskCount > 0) {
            $actions[] = "{$highRiskCount} ternak berisiko tinggi - perlu perhatian segera";
        }

        if ($needsVaccination > 0) {
            $actions[] = "Jadwalkan vaksinasi untuk {$needsVaccination} ternak";
        }

        if ($needsCheckup > 0) {
            $actions[] = "{$needsCheckup} ternak perlu pemeriksaan kesehatan rutin";
        }

        if (empty($actions)) {
            $actions[] = "Farm dalam kondisi baik - pertahankan perawatan rutin";
        }

        return [
            'total_animals' => $animals->count(),
            'high_risk' => $highRiskCount,
            'medium_risk' => $mediumRiskCount,
            'low_risk' => $animals->count() - $highRiskCount - $mediumRiskCount,
            'actions' => $actions
        ];
    }

    /**
     * Get high risk animals for a user
     */
    public function getHighRiskAnimals(int $userId, int $limit = 5)
    {
        $animals = Animal::where('user_id', $userId)
            ->with([
                'healthRecords' => function ($query) {
                    $query->latest('tanggal_pemeriksaan')->limit(3);
                }
            ])
            ->get();

        return $animals->map(function ($animal) {
            $animal->risk_score = $this->calculateHealthRiskScore($animal);
            $animal->risk_level = $this->getRiskLevel($animal->risk_score);
            $animal->recommendations = $this->generateSmartRecommendations($animal);
            return $animal;
        })
            ->filter(fn($a) => $a->risk_score >= 60)
            ->sortByDesc('risk_score')
            ->take($limit)
            ->values();
    }

    /**
     * Get risk level label
     */
    private function getRiskLevel(int $score): string
    {
        if ($score >= 80)
            return 'Tinggi';
        if ($score >= 60)
            return 'Sedang';
        return 'Rendah';
    }
}
