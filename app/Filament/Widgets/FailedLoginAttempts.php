<?php

namespace App\Filament\Widgets;

use App\Models\LoginHistory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class FailedLoginAttempts extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                LoginHistory::query()
                    ->where('login_status', 'failed')
                    ->latest('login_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->default('Unknown'),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable(),

                Tables\Columns\TextColumn::make('device_type')
                    ->label('Device')
                    ->badge()
                    ->colors([
                        'primary' => 'desktop',
                        'success' => 'mobile',
                        'warning' => 'tablet',
                    ]),

                Tables\Columns\TextColumn::make('browser')
                    ->label('Browser')
                    ->limit(20),

                Tables\Columns\TextColumn::make('login_at')
                    ->label('Attempted At')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->heading('Recent Failed Login Attempts')
            ->defaultSort('login_at', 'desc');
    }
}
