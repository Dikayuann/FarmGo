<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Select::make('role')
                            ->label('Role')
                            ->options([
                                User::ROLE_ADMIN => 'Admin',
                                User::ROLE_PREMIUM => 'Peternak Premium',
                                User::ROLE_TRIAL => 'Peternak Trial',
                            ])
                            ->default(User::ROLE_TRIAL)
                            ->required()
                            ->native(false),

                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->helperText('Leave blank to keep current password'),
                    ])
                    ->columns(2),

                Section::make('Farm Information')
                    ->schema([
                        TextInput::make('farm_name')
                            ->label('Farm Name')
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(20),
                    ])
                    ->columns(2),

                Section::make('OAuth Information')
                    ->schema([
                        TextInput::make('google_id')
                            ->label('Google ID')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('avatar')
                            ->label('Avatar URL')
                            ->url()
                            ->maxLength(255),

                        DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
