<?php

namespace App\Filament\Resources\OrdreProductions\Pages;

use App\Filament\Resources\OrdreProductions\OrdreProductionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrdreProductions extends ListRecords
{
    protected static string $resource = OrdreProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
