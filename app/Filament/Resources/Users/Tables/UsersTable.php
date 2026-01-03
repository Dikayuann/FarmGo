<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        User::ROLE_ADMIN => 'danger',
                        User::ROLE_PREMIUM => 'success',
                        User::ROLE_TRIAL => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        User::ROLE_ADMIN => 'Admin',
                        User::ROLE_PREMIUM => 'Premium',
                        User::ROLE_TRIAL => 'Trial',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('farm_name')
                    ->label('Farm')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status_langganan')
                    ->label('Status Langganan')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'premium' => 'success',
                        'trial' => 'warning',
                        'expired' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->sortable(),

                TextColumn::make('batas_ternak')
                    ->label('Batas Ternak')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->suffix(' hewan'),

                TextColumn::make('batas_vaksin')
                    ->label('Batas Vaksin')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->suffix(' vaksin'),

                TextColumn::make('batas_reproduksi')
                    ->label('Batas Reproduksi')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->suffix(' reproduksi'),

                IconColumn::make('google_id')
                    ->label('OAuth')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->alignCenter()
                    ->toggleable(),

                TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        User::ROLE_ADMIN => 'Admin',
                        User::ROLE_PREMIUM => 'Premium',
                        User::ROLE_TRIAL => 'Trial',
                    ])
                    ->multiple()
                    ->native(false),

                SelectFilter::make('status_langganan')
                    ->label('Status Langganan')
                    ->options([
                        'trial' => 'Trial',
                        'premium' => 'Premium',
                        'expired' => 'Expired',
                    ])
                    ->multiple()
                    ->native(false),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
