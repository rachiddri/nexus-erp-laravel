<?php

namespace App\Filament\Resources\OrdreProductionLignes\Pages;

use App\Filament\Resources\OrdreProductionLignes\OrdreProductionLigneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrdreProductionLignes extends ListRecords
{
    protected static string $resource = OrdreProductionLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
