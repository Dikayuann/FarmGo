<?php

namespace App\Filament\Widgets;

use App\Models\HealthRecord;
use App\Models\Notifikasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HealthRecordsSummaryWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        // Health records this month
        $healthRecordsThisMonth = HealthRecord::whereMonth('tanggal_pemeriksaan', now()->month)
            ->whereYear('tanggal_pemeriksaan', now()->year)
            ->count();

        $lastMonthRecords = HealthRecord::whereMonth('tanggal_pemeriksaan', now()->subMonth()->month)
            ->whereYear('tanggal_pemeriksaan', now()->subMonth()->year)
            ->count();

        $recordsChange = $lastMonthRecords > 0
            ? (($healthRecordsThisMonth - $lastMonthRecords) / $lastMonthRecords) * 100
            : 0;

        // Upcoming vaccinations (from notifications)
        $upcomingVaccinations = Notifikasi::where('jenis_notifikasi', 'vaksinasi_berikutnya')
            ->where('tanggal_kirim', '>=', now()->toDateString())
            ->where('tanggal_kirim', '<=', now()->addDays(30)->toDateString())
            ->count();

        // Recent health issues (last 7 days)
        $recentHealthIssues = HealthRecord::where('tanggal_pemeriksaan', '>=', now()->subDays(7)->toDateString())
            ->whereNotNull('diagnosis')
            ->count();

        // Total health records
        $totalHealthRecords = HealthRecord::count();

        return [
            Stat::make('Health Records This Month', $healthRecordsThisMonth)
                ->description(($recordsChange >= 0 ? '+' : '') . number_format($recordsChange, 1) . '% from last month')
                ->descriptionIcon($recordsChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($recordsChange >= 0 ? 'success' : 'danger'),

            Stat::make('Upcoming Vaccinations', $upcomingVaccinations)
                ->description('Next 30 days')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),

            Stat::make('Recent Health Issues', $recentHealthIssues)
                ->description('Last 7 days')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($recentHealthIssues > 0 ? 'danger' : 'success'),
        ];
    }
}
