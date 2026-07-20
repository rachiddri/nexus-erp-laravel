<?php

namespace App\Filament\Resources\FactureLignes\Pages;

use App\Filament\Resources\FactureLignes\FactureLigneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFactureLignes extends ListRecords
{
    protected static string $resource = FactureLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
