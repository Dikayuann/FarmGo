<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Admin Users', User::where('role', User::ROLE_ADMIN)->count())
                ->description('System administrators')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('danger'),

            Stat::make('Premium Users', User::where('role', User::ROLE_PREMIUM)->count())
                ->description('Peternak Premium members')
                ->descriptionIcon('heroicon-m-star')
                ->color('success'),

            Stat::make('Trial Users', User::where('role', User::ROLE_TRIAL)->count())
                ->description('Peternak Trial members')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
