<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends StatsOverviewWidget
{
    // Refresh widget every 5 minutes
    protected ?string $pollingInterval = '300s';

    protected function getStats(): array
    {
        // Cache the stats for 5 minutes to reduce database load
        $totalUsers = Cache::remember('users_total_count', 300, function () {
            return User::count();
        });

        $adminCount = Cache::remember('users_admin_count', 300, function () {
            return User::where('role', User::ROLE_ADMIN)->count();
        });

        $premiumCount = Cache::remember('users_premium_count', 300, function () {
            return User::where('role', User::ROLE_PREMIUM)->count();
        });

        $trialCount = Cache::remember('users_trial_count', 300, function () {
            return User::where('role', User::ROLE_TRIAL)->count();
        });

        return [
            Stat::make('Total Users', $totalUsers)
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Admin Users', $adminCount)
                ->description('System administrators')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('danger'),

            Stat::make('Premium Users', $premiumCount)
                ->description('Peternak Premium members')
                ->descriptionIcon('heroicon-m-star')
                ->color('success'),

            Stat::make('Trial Users', $trialCount)
                ->description('Peternak Trial members')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
