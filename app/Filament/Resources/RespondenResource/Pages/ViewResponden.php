<?php

namespace App\Filament\Resources\RespondenResource\Pages;

use App\Filament\Resources\RespondenResource;
use Filament\Resources\Pages\ViewRecord;

class ViewResponden extends ViewRecord
{
    protected static string $resource = RespondenResource::class;

    // Pakai blade custom sendiri
    protected static string $view = 'filament.resources.responden-resource.pages.view-responden';
}
