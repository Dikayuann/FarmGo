<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Langganan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ConversionMetricsWidget extends BaseWidget
{
    protected static ?int $sort = 7;

    protected function getStats(): array
    {
        // Active trial users (current)
        $activeTrialUsers = User::where('role', 'peternak_trial')->count();

        // Premium users (current)
        $premiumUsers = User::where('role', 'peternak_premium')->count();

        // Total users who ever had trial
        $totalTrialUsers = Langganan::where('paket_langganan', 'trial')
            ->distinct('user_id')
            ->count('user_id');

        // Users who converted from trial to premium (had trial before, now premium)
        $convertedUsers = User::where('role', 'peternak_premium')
            ->whereHas('langganans', function ($query) {
                $query->where('paket_langganan', 'trial');
            })
            ->count();

        // Conversion rate
        $conversionRate = $totalTrialUsers > 0
            ? ($convertedUsers / $totalTrialUsers) * 100
            : 0;

        // Description for conversion rate
        $conversionDescription = $totalTrialUsers > 0
            ? $convertedUsers . ' of ' . $totalTrialUsers . ' trials converted'
            : 'No trial data yet';

        return [
            Stat::make('Trial Conversion Rate', number_format($conversionRate, 1) . '%')
                ->description($conversionDescription)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($conversionRate >= 30 ? 'success' : ($conversionRate >= 15 ? 'warning' : 'danger')),

            Stat::make('Active Trial Users', $activeTrialUsers)
                ->description($activeTrialUsers > 0 ? 'Currently on trial' : 'No active trials')
                ->descriptionIcon('heroicon-m-user-group')
                ->color($activeTrialUsers > 0 ? 'warning' : 'secondary'),

            Stat::make('Premium Users', $premiumUsers)
                ->description($premiumUsers > 0 ? 'Paying customers' : 'No premium users yet')
                ->descriptionIcon('heroicon-m-star')
                ->color($premiumUsers > 0 ? 'success' : 'secondary'),
        ];
    }
}
