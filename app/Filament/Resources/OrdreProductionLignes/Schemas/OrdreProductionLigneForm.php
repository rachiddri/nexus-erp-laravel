<?php

namespace App\Filament\Resources\OrdreProductionLignes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrdreProductionLigneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('ordre_production_id')
                    ->relationship('ordreProduction', 'numero_op')
                    ->required(),
                Select::make('produit_id')
                    ->relationship('produit', 'nom')
                    ->required(),
                TextInput::make('quantite')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('quantite_produite')
                    ->numeric()
                    ->default(0),
                TextInput::make('quantite_rebutee')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
