<?php

namespace App\Filament\Resources\MatierePremierePrixHistoriques\Pages;

use App\Filament\Resources\MatierePremierePrixHistoriques\MatierePremierePrixHistoriqueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMatierePremierePrixHistorique extends EditRecord
{
    protected static string $resource = MatierePremierePrixHistoriqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
