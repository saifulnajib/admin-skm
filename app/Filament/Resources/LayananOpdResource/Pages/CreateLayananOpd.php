<?php

namespace App\Filament\Resources\LayananOpdResource\Pages;

use App\Filament\Resources\LayananOpdResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLayananOpd extends CreateRecord
{
    protected static string $resource = LayananOpdResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
