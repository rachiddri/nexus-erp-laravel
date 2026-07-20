<?php

namespace App\Filament\Resources\InventaireLignes\Pages;

use App\Filament\Resources\InventaireLignes\InventaireLigneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInventaireLigne extends EditRecord
{
    protected static string $resource = InventaireLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
