<?php

namespace App\Filament\Resources\Langganans\Pages;

use App\Filament\Resources\Langganans\LanggananResource;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewLangganan extends ViewRecord
{
    protected static string $resource = LanggananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),

            Action::make('activate')
                ->label('Aktifkan Langganan')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->activate();
                    Notification::make()
                        ->title('Langganan berhasil diaktifkan')
                        ->success()
                        ->send();
                    $this->refreshFormData([
                        'status',
                        'tanggal_mulai',
                    ]);
                })
                ->visible(fn() => $this->record->status !== 'aktif'),

            Action::make('cancel')
                ->label('Batalkan Langganan')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->cancel();
                    Notification::make()
                        ->title('Langganan berhasil dibatalkan')
                        ->success()
                        ->send();
                    $this->refreshFormData([
                        'status',
                        'auto_renew',
                        'cancelled_at',
                    ]);
                })
                ->visible(fn() => $this->record->status === 'aktif'),

            DeleteAction::make(),
        ];
    }
}

