<?php

namespace App\Filament\Resources\ProduitEtapes\Pages;

use App\Filament\Resources\ProduitEtapes\ProduitEtapeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduitEtape extends EditRecord
{
    protected static string $resource = ProduitEtapeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
