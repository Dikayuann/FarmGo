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
                TextInput::make('kode_hewan')
                    ->label('Kode Hewan')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('nama_hewan')
                    ->label('Nama Hewan')
                    ->required()
                    ->maxLength(255),

                Select::make('jenis_hewan')
                    ->label('Jenis Hewan')
                    ->options([
                        'sapi' => 'Sapi',
                        'kambing' => 'Kambing',
                        'domba' => 'Domba',
                    ])
                    ->required()
                    ->native(false),

                TextInput::make('ras_hewan')
                    ->label('Ras Hewan')
                    ->required()
                    ->maxLength(255),

                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required()
                    ->native(false)
                    ->maxDate(now()),

                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'jantan' => 'Jantan',
                        'betina' => 'Betina',
                    ])
                    ->required()
                    ->native(false),

                TextInput::make('berat_badan')
                    ->label('Berat Badan (kg)')
                    ->required()
                    ->numeric()
                    ->suffix('kg')
                    ->minValue(0),

                Select::make('status_ternak')
                    ->label('Status Ternak')
                    ->options([
                        'sehat' => 'Sehat',
                        'sakit' => 'Sakit',
                        'karantina' => 'Karantina',
                    ])
                    ->default('sehat')
                    ->required()
                    ->native(false),

                Select::make('user_id')
                    ->label('Pemilik')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Pilih pemilik hewan'),
            ]);
    }
}
