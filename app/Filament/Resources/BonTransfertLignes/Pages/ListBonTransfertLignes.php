<?php

namespace App\Filament\Resources\BonTransfertLignes\Pages;

use App\Filament\Resources\BonTransfertLignes\BonTransfertLigneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBonTransfertLignes extends ListRecords
{
    protected static string $resource = BonTransfertLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
