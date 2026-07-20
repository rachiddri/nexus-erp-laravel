<?php

namespace App\Filament\Resources\FactureLignes\Pages;

use App\Filament\Resources\FactureLignes\FactureLigneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFactureLigne extends EditRecord
{
    protected static string $resource = FactureLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
