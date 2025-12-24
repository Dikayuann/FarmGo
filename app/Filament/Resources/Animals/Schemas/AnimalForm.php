<?php

namespace App\Filament\Resources\Animals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AnimalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->options(['Sapi' => 'Sapi', 'Kambing' => 'Kambing', 'Domba' => 'Domba', 'Banteng' => 'Banteng'])
                    ->required(),
                DatePicker::make('birth_date')
                    ->required(),
                TextInput::make('weight')
                    ->required()
                    ->numeric(),
                Select::make('condition')
                    ->options([
            'Sangat Baik' => 'Sangat baik',
            'Baik' => 'Baik',
            'Cukup' => 'Cukup',
            'Kurang' => 'Kurang',
            'Sakit' => 'Sakit',
        ])
                    ->required(),
            ]);
    }
}
