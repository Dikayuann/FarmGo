<?php

namespace App\Filament\Resources\Animals\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AnimalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->schema([
                        TextEntry::make('kode_hewan')
                            ->label('Kode Hewan')
                            ->copyable(),

                        TextEntry::make('nama_hewan')
                            ->label('Nama Hewan'),

                        TextEntry::make('user.name')
                            ->label('Pemilik'),
                    ])
                    ->columns(3),

                Section::make('Detail Hewan')
                    ->schema([
                        TextEntry::make('jenis_hewan')
                            ->label('Jenis Hewan')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => ucfirst($state))
                            ->color(fn (string $state): string => match($state) {
                                'sapi' => 'info',
                                'kambing' => 'warning',
                                'domba' => 'success',
                                default => 'gray',
                            }),

                        TextEntry::make('ras_hewan')
                            ->label('Ras Hewan'),

                        TextEntry::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => ucfirst($state))
                            ->color(fn (string $state): string => match($state) {
                                'jantan' => 'primary',
                                'betina' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->date('d F Y'),

                        TextEntry::make('berat_badan')
                            ->label('Berat Badan')
                            ->suffix(' kg')
                            ->numeric(decimalPlaces: 2),

                        TextEntry::make('status_ternak')
                            ->label('Status Ternak')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => ucfirst($state))
                            ->color(fn (string $state): string => match($state) {
                                'sehat' => 'success',
                                'sakit' => 'danger',
                                'karantina' => 'warning',
                                default => 'gray',
                            }),
                    ])
                    ->columns(3),

                Section::make('Informasi Tambahan')
                    ->schema([
                        TextEntry::make('qr_url')
                            ->label('QR Code URL')
                            ->copyable()
                            ->placeholder('Belum ada QR Code'),

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d F Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diupdate')
                            ->dateTime('d F Y H:i'),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }
}

