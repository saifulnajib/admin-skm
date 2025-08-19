<?php

namespace App\Filament\Resources\LayananOpdResource\Pages;

use App\Filament\Resources\LayananOpdResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLayananOpds extends ListRecords
{
    protected static string $resource = LayananOpdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah')
                    ->icon('heroicon-o-plus'),
        ];
    }
}
