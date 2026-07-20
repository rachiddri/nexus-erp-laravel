<?php

namespace App\Filament\Resources\InventaireProduitPhysiques\Pages;

use App\Filament\Resources\InventaireProduitPhysiques\InventaireProduitPhysiqueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInventaireProduitPhysique extends EditRecord
{
    protected static string $resource = InventaireProduitPhysiqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
