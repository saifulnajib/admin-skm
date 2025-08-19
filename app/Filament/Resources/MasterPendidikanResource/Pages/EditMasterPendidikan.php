<?php

namespace App\Filament\Resources\MasterPendidikanResource\Pages;

use App\Filament\Resources\MasterPendidikanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterPendidikan extends EditRecord
{
    protected static string $resource = MasterPendidikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
