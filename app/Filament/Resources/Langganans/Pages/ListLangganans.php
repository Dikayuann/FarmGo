<?php

namespace App\Filament\Resources\Langganans\Pages;

use App\Filament\Resources\Langganans\LanggananResource;
use App\Exports\LangganansExport;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ListLangganans extends ListRecords
{
    protected static string $resource = LanggananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $filename = 'data-langganan-' . Carbon::now()->format('Y-m-d-His') . '.xlsx';

                    try {
                        return Excel::download(new LangganansExport(), $filename);
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

    /**
     * Eager load relationships to prevent N+1 queries
     */
    protected function getTableQuery(): ?Builder
    {
        return static::getResource()::getEloquentQuery()->with('user:id,name,email');
    }
}

