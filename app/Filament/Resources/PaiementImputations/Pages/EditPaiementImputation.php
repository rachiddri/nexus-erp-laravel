<?php

namespace App\Filament\Resources\PaiementImputations\Pages;

use App\Filament\Resources\PaiementImputations\PaiementImputationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPaiementImputation extends EditRecord
{
    protected static string $resource = PaiementImputationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
