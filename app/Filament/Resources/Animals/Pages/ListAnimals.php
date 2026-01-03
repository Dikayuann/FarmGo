<?php

namespace App\Filament\Resources\Animals\Pages;

use App\Filament\Resources\Animals\AnimalResource;
use App\Filament\Resources\Animals\Widgets\AnimalsStatsOverview;
use App\Exports\AnimalsExport;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ListAnimals extends ListRecords
{
    protected static string $resource = AnimalResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            AnimalsStatsOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $filename = 'data-hewan-' . Carbon::now()->format('Y-m-d-His') . '.xlsx';

                    try {
                        return Excel::download(new AnimalsExport(), $filename);
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Export Gagal')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            CreateAction::make(),
        ];
    }
}
