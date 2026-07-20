<?php

namespace App\Filament\Resources\LotProduits\Pages;

use App\Filament\Resources\LotProduits\LotProduitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLotProduit extends EditRecord
{
    protected static string $resource = LotProduitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
