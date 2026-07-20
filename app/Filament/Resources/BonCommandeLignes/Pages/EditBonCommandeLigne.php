<?php

namespace App\Filament\Resources\BonCommandeLignes\Pages;

use App\Filament\Resources\BonCommandeLignes\BonCommandeLigneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBonCommandeLigne extends EditRecord
{
    protected static string $resource = BonCommandeLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
