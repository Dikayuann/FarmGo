<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Animal;
use App\Models\Langganan;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Users
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $activeUsers = User::where('role', '!=', 'admin')
            ->where(function ($query) {
                $query->where('role', 'premium')
                    ->orWhere('role', 'trial');
            })
            ->count();

        // Total Animals
        $totalAnimals = Animal::count();
        $newAnimalsThisMonth = Animal::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Active Subscriptions
        $activeSubscriptions = Langganan::where('status', 'active')
            ->where('end_date', '>=', now())
            ->count();

        // Monthly Revenue
        $monthlyRevenue = Transaction::where('status', 'settlement')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $lastMonthRevenue = Transaction::where('status', 'settlement')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        $revenueChange = $lastMonthRevenue > 0
            ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        return [
            Stat::make('Total Users', $totalUsers)
                ->description($activeUsers . ' active users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Animals', $totalAnimals)
                ->description('+' . $newAnimalsThisMonth . ' this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('Active Subscriptions', $activeSubscriptions)
                ->description('Currently active')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('warning'),

            Stat::make('Monthly Revenue', 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
                ->description(($revenueChange >= 0 ? '+' : '') . number_format($revenueChange, 1) . '% from last month')
                ->descriptionIcon($revenueChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueChange >= 0 ? 'success' : 'danger'),
        ];
    }
}
