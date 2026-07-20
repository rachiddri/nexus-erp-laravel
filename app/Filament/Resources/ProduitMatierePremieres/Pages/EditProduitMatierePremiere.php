<?php

namespace App\Filament\Resources\ProduitMatierePremieres\Pages;

use App\Filament\Resources\ProduitMatierePremieres\ProduitMatierePremiereResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduitMatierePremiere extends EditRecord
{
    protected static string $resource = ProduitMatierePremiereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
