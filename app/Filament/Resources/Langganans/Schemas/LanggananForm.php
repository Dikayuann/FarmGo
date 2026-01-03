<?php

namespace App\Filament\Resources\Langganans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use App\Models\User;

class LanggananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Pilih pengguna yang akan berlangganan'),

                Select::make('paket_langganan')
                    ->label('Paket')
                    ->options([
                        'trial' => 'Trial',
                        'premium_monthly' => 'Premium Bulanan',
                        'premium_yearly' => 'Premium Tahunan',
                    ])
                    ->required()
                    ->native(false)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Set harga otomatis berdasarkan paket
                        $harga = match ($state) {
                            'trial' => 0,
                            'premium_monthly' => 50000,
                            'premium_yearly' => 500000,
                            default => 0,
                        };
                        $set('harga', $harga);
                    }),

                TextInput::make('harga')
                    ->label('Harga (Rp)')
                    ->numeric()
                    ->required()
                    ->prefix('Rp')
                    ->helperText('Harga langganan dalam Rupiah'),

                Select::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->options([
                        'midtrans' => 'Midtrans',
                        'manual_transfer' => 'Transfer Manual',
                        'other' => 'Lainnya',
                    ])
                    ->required()
                    ->native(false)
                    ->default('midtrans')
                    ->helperText('Pilih metode pembayaran yang digunakan'),

                TextInput::make('transaction_reference')
                    ->label('ID Transaksi / Referensi')
                    ->maxLength(255)
                    ->helperText('Order ID atau referensi transaksi dari payment gateway')
                    ->placeholder('Misal: ORDER-123456 atau TRX-ABC123'),

                DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->native(false)
                    ->default(now())
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // Set tanggal berakhir otomatis berdasarkan paket
                        $paket = $get('paket_langganan');
                        if ($state && $paket) {
                            $tanggalMulai = \Carbon\Carbon::parse($state);
                            $tanggalBerakhir = match ($paket) {
                                'trial' => $tanggalMulai->copy()->addDays(7),
                                'premium_monthly' => $tanggalMulai->copy()->addMonth(),
                                'premium_yearly' => $tanggalMulai->copy()->addYear(),
                                default => $tanggalMulai->copy()->addMonth(),
                            };
                            $set('tanggal_berakhir', $tanggalBerakhir->format('Y-m-d'));
                        }
                    }),

                DatePicker::make('tanggal_berakhir')
                    ->label('Tanggal Berakhir')
                    ->required()
                    ->native(false)
                    ->minDate(fn(callable $get) => $get('tanggal_mulai')),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'kadaluarsa' => 'Kadaluarsa',
                        'dibatalkan' => 'Dibatalkan',
                    ])
                    ->required()
                    ->native(false)
                    ->default('aktif'),

                Toggle::make('auto_renew')
                    ->label('Perpanjang Otomatis')
                    ->helperText('Aktifkan untuk perpanjangan otomatis saat berakhir')
                    ->default(false),
            ]);
    }
}

