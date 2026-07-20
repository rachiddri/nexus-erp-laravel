<?php

namespace App\Filament\Resources\RetourClientLignes\Pages;

use App\Filament\Resources\RetourClientLignes\RetourClientLigneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRetourClientLigne extends EditRecord
{
    protected static string $resource = RetourClientLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
