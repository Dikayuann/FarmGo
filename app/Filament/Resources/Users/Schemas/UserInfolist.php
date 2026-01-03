<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Full Name'),

                TextEntry::make('email')
                    ->label('Email Address')
                    ->copyable(),

                TextEntry::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'admin' => 'danger',
                        'premium' => 'success',
                        'trial' => 'warning',
                        default => 'gray',
                    }),

                TextEntry::make('farm_name')
                    ->label('Farm Name')
                    ->placeholder('-'),

                TextEntry::make('phone')
                    ->label('Phone Number')
                    ->placeholder('-'),

                TextEntry::make('status_langganan')
                    ->label('Status Langganan')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'premium' => 'success',
                        'trial' => 'warning',
                        'expired' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextEntry::make('batas_ternak')
                    ->label('Batas Ternak')
                    ->numeric()
                    ->suffix(' hewan'),

                TextEntry::make('batas_vaksin')
                    ->label('Batas Vaksin')
                    ->numeric()
                    ->suffix(' vaksin'),

                TextEntry::make('batas_reproduksi')
                    ->label('Batas Reproduksi')
                    ->numeric()
                    ->suffix(' reproduksi'),

                TextEntry::make('google_id')
                    ->label('Google ID')
                    ->placeholder('Not connected')
                    ->copyable(),

                TextEntry::make('avatar')
                    ->label('Avatar URL')
                    ->placeholder('-')
                    ->limit(50),

                TextEntry::make('email_verified_at')
                    ->label('Email Verified At')
                    ->dateTime('d F Y H:i')
                    ->placeholder('Not verified'),

                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime('d F Y H:i'),

                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('d F Y H:i'),
            ]);
    }
}
