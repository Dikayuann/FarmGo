<?php

namespace App\Filament\Resources\Langganans\Pages;

use App\Filament\Resources\Langganans\LanggananResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLangganan extends CreateRecord
{
    protected static string $resource = LanggananResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

