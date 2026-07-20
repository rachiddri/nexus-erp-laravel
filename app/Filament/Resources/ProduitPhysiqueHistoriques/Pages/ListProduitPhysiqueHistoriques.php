<?php

namespace App\Filament\Resources\ProduitPhysiqueHistoriques\Pages;

use App\Filament\Resources\ProduitPhysiqueHistoriques\ProduitPhysiqueHistoriqueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProduitPhysiqueHistoriques extends ListRecords
{
    protected static string $resource = ProduitPhysiqueHistoriqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
