<?php

namespace App\Filament\Resources\MasterPekerjaanResource\Pages;

use App\Filament\Resources\MasterPekerjaanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMasterPekerjaan extends CreateRecord
{
    protected static string $resource = MasterPekerjaanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
