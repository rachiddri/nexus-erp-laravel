<?php

namespace App\Filament\Resources\MouvementStockMatieres\Pages;

use App\Filament\Resources\MouvementStockMatieres\MouvementStockMatiereResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMouvementStockMatiere extends EditRecord
{
    protected static string $resource = MouvementStockMatiereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
