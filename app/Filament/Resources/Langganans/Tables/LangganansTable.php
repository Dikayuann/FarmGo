<?php

namespace App\Filament\Resources\Langganans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LangganansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => $record->user->email),

                TextColumn::make('paket_langganan')
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
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('tanggal_berakhir')
                    ->label('Berakhir')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn($record) => $record->isExpired() ? 'danger' : 'success')
                    ->description(fn($record) => $record->daysRemaining() > 0
                        ? 'Sisa ' . $record->daysRemaining() . ' hari'
                        : 'Sudah berakhir'),

                TextColumn::make('status')
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
                    })
                    ->sortable(),

                IconColumn::make('auto_renew')
                    ->label('Auto Renew')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->alignCenter()
                    ->toggleable(),

                TextColumn::make('metode_pembayaran')
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
                    })
                    ->sortable(),

                TextColumn::make('transaction_reference')
                    ->label('ID Transaksi')
                    ->searchable()
                    ->copyable()
                    ->placeholder('Tidak ada')
                    ->limit(20)
                    ->tooltip(fn($record) => $record->transaction_reference)
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'kadaluarsa' => 'Kadaluarsa',
                        'dibatalkan' => 'Dibatalkan',
                    ])
                    ->native(false),

                SelectFilter::make('paket_langganan')
                    ->label('Paket')
                    ->options([
                        'trial' => 'Trial',
                        'premium_monthly' => 'Premium Bulanan',
                        'premium_yearly' => 'Premium Tahunan',
                    ])
                    ->native(false),

                SelectFilter::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->options([
                        'midtrans' => 'Midtrans',
                        'manual_transfer' => 'Transfer Manual',
                        'other' => 'Lainnya',
                    ])
                    ->native(false),

                Filter::make('akan_kadaluarsa')
                    ->label('Akan Kadaluarsa (7 Hari)')
                    ->query(fn(Builder $query): Builder => $query->expiringSoon(7)),

                Filter::make('auto_renew')
                    ->label('Auto Renew Aktif')
                    ->query(fn(Builder $query): Builder => $query->where('auto_renew', true)),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('activate')
                    ->label('Aktifkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->activate())
                    ->visible(fn($record) => $record->status !== 'aktif'),
                Action::make('cancel')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->cancel())
                    ->visible(fn($record) => $record->status === 'aktif'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

