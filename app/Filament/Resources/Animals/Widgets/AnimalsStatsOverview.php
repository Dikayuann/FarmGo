<?php

namespace App\Filament\Resources\Animals\Widgets;

use App\Models\Animal;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class AnimalsStatsOverview extends StatsOverviewWidget
{
    // Refresh widget every 5 minutes
    protected ?string $pollingInterval = '300s';

    protected function getStats(): array
    {
        // Cache the stats for 5 minutes to reduce database load
        $totalAnimals = Cache::remember('animals_total_count', 300, function () {
            return Animal::count();
        });

        $animalsByType = Cache::remember('animals_by_type_count', 300, function () {
            return Animal::selectRaw('jenis_hewan, COUNT(*) as count')
                ->groupBy('jenis_hewan')
                ->pluck('count', 'jenis_hewan')
                ->toArray();
        });

        $stats = [
            Stat::make('Total Hewan Tercatat', $totalAnimals)
                ->description('Jumlah seluruh hewan di sistem')
                ->descriptionIcon('heroicon-o-rectangle-stack')
                ->color('success')
                ->chart([7, 12, 18, 15, 22, 28, $totalAnimals]),
        ];

        // Tambahkan stats per jenis hewan
        foreach ($animalsByType as $jenisHewan => $count) {
            // Capitalize untuk display
            $displayName = ucfirst($jenisHewan);

            $icon = match(strtolower($jenisHewan)) {
                'sapi' => 'heroicon-o-heart',
                'kambing' => 'heroicon-o-star',
                'domba' => 'heroicon-o-sparkles',
                default => 'heroicon-o-check-circle',
            };

            $color = match(strtolower($jenisHewan)) {
                'sapi' => 'info',
                'kambing' => 'warning',
                'domba' => 'success',
                default => 'gray',
            };

            $stats[] = Stat::make($displayName, $count)
                ->description("Jumlah $displayName")
                ->descriptionIcon($icon)
                ->color($color);
        }

        return $stats;
    }
}

