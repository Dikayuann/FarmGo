<?php

namespace App\Filament\Widgets;

use App\Models\Langganan;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ExpiringSubscriptionsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Langganan::query()
                    ->where('status', 'aktif')
                    ->where('tanggal_berakhir', '>=', now()->toDateString())
                    ->where('tanggal_berakhir', '<=', now()->addDays(14)->toDateString())
                    ->with('user')
                    ->orderBy('tanggal_berakhir', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_reference')
                    ->label('Transaction ID')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Transaction ID copied!')
                    ->placeholder('N/A')
                    ->limit(20),
                Tables\Columns\BadgeColumn::make('paket_langganan')
                    ->label('Package')
                    ->colors([
                        'warning' => 'trial',
                        'success' => 'premium_monthly',
                        'primary' => 'premium_yearly',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'trial' => 'Trial',
                        'premium_monthly' => 'Premium Monthly',
                        'premium_yearly' => 'Premium Yearly',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('tanggal_berakhir')
                    ->label('Expires')
                    ->date('d M Y')
                    ->sortable()
                    ->badge()
                    ->color(fn($record) => now()->diffInDays($record->tanggal_berakhir) <= 3 ? 'danger' : 'warning'),
                Tables\Columns\TextColumn::make('tanggal_berakhir')
                    ->label('Days Left')
                    ->formatStateUsing(function ($record) {
                        $days = (int) now()->diffInDays($record->tanggal_berakhir, false);
                        return $days . ' hari';
                    })
                    ->badge()
                    ->color(fn($record) => now()->diffInDays($record->tanggal_berakhir) <= 3 ? 'danger' : 'warning'),
            ])
            ->heading('Subscriptions Expiring Soon (Next 14 Days)')
            ->defaultSort('tanggal_berakhir', 'asc')
            ->paginated([5, 10]);
    }
}
