<?php

namespace App\Filament\Resources\ProduitPhysiques\Pages;

use App\Filament\Resources\ProduitPhysiques\ProduitPhysiqueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduitPhysique extends EditRecord
{
    protected static string $resource = ProduitPhysiqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
