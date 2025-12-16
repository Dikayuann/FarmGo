<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('google_id')
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('role')
                    ->badge(),
                TextEntry::make('avatar')
                    ->placeholder('-'),
                TextEntry::make('email_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('status_langganan')
                    ->badge(),
                TextEntry::make('batas_ternak')
                    ->numeric(),
                TextEntry::make('batas_vaksin')
                    ->numeric(),
                TextEntry::make('batas_reproduksi')
                    ->numeric(),
                TextEntry::make('farm_name')
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->placeholder('-'),
            ]);
    }
}
