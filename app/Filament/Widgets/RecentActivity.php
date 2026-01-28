<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Animal;
use App\Models\Langganan;
use App\Models\LoginHistory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivity extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Combine recent activities from different sources
                User::query()
                    ->where('role', '!=', 'admin')
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('role')
                    ->label('Status')
                    ->colors([
                        'success' => 'premium',
                        'warning' => 'trial',
                        'danger' => 'inactive',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->heading('Recent User Registrations')
            ->defaultSort('created_at', 'desc');
    }
}
