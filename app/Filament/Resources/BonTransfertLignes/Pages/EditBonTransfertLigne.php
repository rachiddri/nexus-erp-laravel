<?php

namespace App\Filament\Resources\BonTransfertLignes\Pages;

use App\Filament\Resources\BonTransfertLignes\BonTransfertLigneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBonTransfertLigne extends EditRecord
{
    protected static string $resource = BonTransfertLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
