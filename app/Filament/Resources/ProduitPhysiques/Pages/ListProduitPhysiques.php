<?php

namespace App\Filament\Resources\ProduitPhysiques\Pages;

use App\Filament\Resources\ProduitPhysiques\ProduitPhysiqueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProduitPhysiques extends ListRecords
{
    protected static string $resource = ProduitPhysiqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
