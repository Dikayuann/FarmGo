<?php

namespace App\Filament\Resources\Animals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AnimalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_hewan')
                    ->label('Kode Hewan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_hewan')
                    ->label('Nama Hewan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_hewan')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match($state) {
                        'sapi' => 'info',
                        'kambing' => 'warning',
                        'domba' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('ras_hewan')
                    ->label('Ras')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match($state) {
                        'jantan' => 'primary',
                        'betina' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),

                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('berat_badan')
                    ->label('Berat Badan')
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' kg')
                    ->sortable(),

                TextColumn::make('status_ternak')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match($state) {
                        'sehat' => 'success',
                        'sakit' => 'danger',
                        'karantina' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Pemilik')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

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
                SelectFilter::make('jenis_hewan')
                    ->label('Jenis Hewan')
                    ->options([
                        'sapi' => 'Sapi',
                        'kambing' => 'Kambing',
                        'domba' => 'Domba',
                    ])
                    ->native(false),

                SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'jantan' => 'Jantan',
                        'betina' => 'Betina',
                    ])
                    ->native(false),

                SelectFilter::make('status_ternak')
                    ->label('Status')
                    ->options([
                        'sehat' => 'Sehat',
                        'sakit' => 'Sakit',
                        'karantina' => 'Karantina',
                    ])
                    ->native(false),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
