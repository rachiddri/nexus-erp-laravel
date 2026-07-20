<?php

namespace App\Filament\Resources\ProduitMatierePremieres\Pages;

use App\Filament\Resources\ProduitMatierePremieres\ProduitMatierePremiereResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProduitMatierePremieres extends ListRecords
{
    protected static string $resource = ProduitMatierePremiereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
