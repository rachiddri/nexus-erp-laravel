<?php

namespace App\Filament\Resources\RetourClientLignes\Pages;

use App\Filament\Resources\RetourClientLignes\RetourClientLigneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRetourClientLignes extends ListRecords
{
    protected static string $resource = RetourClientLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
