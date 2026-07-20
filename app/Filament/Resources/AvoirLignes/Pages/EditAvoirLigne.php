<?php

namespace App\Filament\Resources\AvoirLignes\Pages;

use App\Filament\Resources\AvoirLignes\AvoirLigneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAvoirLigne extends EditRecord
{
    protected static string $resource = AvoirLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
