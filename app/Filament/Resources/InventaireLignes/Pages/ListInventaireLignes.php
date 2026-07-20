<?php

namespace App\Filament\Resources\InventaireLignes\Pages;

use App\Filament\Resources\InventaireLignes\InventaireLigneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventaireLignes extends ListRecords
{
    protected static string $resource = InventaireLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
