<?php

namespace App\Filament\Resources\MatierePremieres\Pages;

use App\Filament\Resources\MatierePremieres\MatierePremiereResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMatierePremiere extends EditRecord
{
    protected static string $resource = MatierePremiereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
