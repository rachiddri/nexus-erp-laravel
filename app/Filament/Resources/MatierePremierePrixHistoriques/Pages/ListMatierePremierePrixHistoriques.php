<?php

namespace App\Filament\Resources\MatierePremierePrixHistoriques\Pages;

use App\Filament\Resources\MatierePremierePrixHistoriques\MatierePremierePrixHistoriqueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMatierePremierePrixHistoriques extends ListRecords
{
    protected static string $resource = MatierePremierePrixHistoriqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
