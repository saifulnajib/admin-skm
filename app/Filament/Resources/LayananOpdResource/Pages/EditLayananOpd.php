<?php

namespace App\Filament\Resources\LayananOpdResource\Pages;

use App\Filament\Resources\LayananOpdResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLayananOpd extends EditRecord
{
    protected static string $resource = LayananOpdResource::class;

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
