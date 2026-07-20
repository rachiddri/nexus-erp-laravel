<?php

namespace App\Filament\Resources\InventaireProduitPhysiques\Pages;

use App\Filament\Resources\InventaireProduitPhysiques\InventaireProduitPhysiqueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventaireProduitPhysiques extends ListRecords
{
    protected static string $resource = InventaireProduitPhysiqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
