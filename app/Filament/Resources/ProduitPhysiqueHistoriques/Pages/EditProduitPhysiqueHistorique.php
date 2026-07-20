<?php

namespace App\Filament\Resources\ProduitPhysiqueHistoriques\Pages;

use App\Filament\Resources\ProduitPhysiqueHistoriques\ProduitPhysiqueHistoriqueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduitPhysiqueHistorique extends EditRecord
{
    protected static string $resource = ProduitPhysiqueHistoriqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
