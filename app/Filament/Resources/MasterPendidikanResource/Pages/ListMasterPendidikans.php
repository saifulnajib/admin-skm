<?php

namespace App\Filament\Resources\MasterPendidikanResource\Pages;

use App\Filament\Resources\MasterPendidikanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterPendidikans extends ListRecords
{
    protected static string $resource = MasterPendidikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah')
                    ->icon('heroicon-o-plus'),
        ];
    }
}
