<?php

namespace App\Filament\Resources\AvoirLignes\Pages;

use App\Filament\Resources\AvoirLignes\AvoirLigneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAvoirLignes extends ListRecords
{
    protected static string $resource = AvoirLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
