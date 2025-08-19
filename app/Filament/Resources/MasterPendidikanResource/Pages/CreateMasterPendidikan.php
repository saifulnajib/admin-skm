<?php

namespace App\Filament\Resources\MasterPendidikanResource\Pages;

use App\Filament\Resources\MasterPendidikanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMasterPendidikan extends CreateRecord
{
    protected static string $resource = MasterPendidikanResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
