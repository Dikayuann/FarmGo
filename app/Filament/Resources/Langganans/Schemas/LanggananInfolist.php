<?php

namespace App\Filament\Resources\Langganans\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;

class LanggananInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengguna')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Nama Pengguna'),

                        TextEntry::make('user.email')
                            ->label('Email')
                            ->copyable(),

                        TextEntry::make('user.farm_name')
                            ->label('Nama Peternakan')
                            ->default('-'),
                    ])
                    ->columns(3),

                Section::make('Detail Langganan')
                    ->schema([
                        TextEntry::make('paket_langganan')
                            ->label('Paket')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'trial' => 'gray',
                                'premium_monthly' => 'success',
                                'premium_yearly' => 'warning',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'trial' => 'Trial',
                                'premium_monthly' => 'Premium Bulanan',
                                'premium_yearly' => 'Premium Tahunan',
                                default => $state,
                            }),

                        TextEntry::make('harga')
                            ->label('Harga')
                            ->money('IDR')
                            ->size('lg')
                            ->weight('bold'),

                        TextEntry::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'midtrans' => 'info',
                                'manual_transfer' => 'warning',
                                'other' => 'gray',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'midtrans' => 'Midtrans',
                                'manual_transfer' => 'Transfer Manual',
                                'other' => 'Lainnya',
                                default => $state,
                            }),

                        TextEntry::make('transaction_reference')
                            ->label('ID Transaksi')
                            ->copyable()
                            ->placeholder('Tidak ada')
                            ->icon('heroicon-o-document-text')
                            ->color('success'),
                    ])
                    ->columns(4),

                Section::make('Periode & Status')
                    ->schema([
                        TextEntry::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->date('d F Y'),

                        TextEntry::make('tanggal_berakhir')
                            ->label('Tanggal Berakhir')
                            ->date('d F Y')
                            ->color(fn($record) => $record->isExpired() ? 'danger' : 'success'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'aktif' => 'success',
                                'kadaluarsa' => 'danger',
                                'dibatalkan' => 'warning',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'aktif' => 'Aktif',
                                'kadaluarsa' => 'Kadaluarsa',
                                'dibatalkan' => 'Dibatalkan',
                                default => $state,
                            }),

                        IconEntry::make('auto_renew')
                            ->label('Perpanjang Otomatis')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('gray'),
                    ])
                    ->columns(4),

                Section::make('Informasi Tambahan')
                    ->schema([
                        TextEntry::make('daysRemaining')
                            ->label('Sisa Hari')
                            ->state(fn($record) => $record->daysRemaining() . ' hari')
                            ->color(fn($record) => match (true) {
                                $record->daysRemaining() <= 0 => 'danger',
                                $record->daysRemaining() <= 7 => 'warning',
                                default => 'success',
                            })
                            ->weight('bold'),

                        TextEntry::make('cancelled_at')
                            ->label('Tanggal Dibatalkan')
                            ->dateTime('d F Y H:i')
                            ->placeholder('-')
                            ->visible(fn($record) => $record->cancelled_at !== null),

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d F Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diupdate')
                            ->dateTime('d F Y H:i'),
                    ])
                    ->columns(4)
                    ->collapsible(),
            ]);
    }
}

