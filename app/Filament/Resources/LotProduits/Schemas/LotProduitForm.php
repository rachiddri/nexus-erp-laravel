<?php

namespace App\Filament\Resources\LotProduits\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LotProduitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lot_id')
                    ->relationship('lot', 'numero_lot')
                    ->required(),
                Select::make('ordre_production_ligne_id')
                    ->relationship('ordreProductionLigne', 'id')
                    ->required(),
                Select::make('produit_id')
                    ->relationship('produit', 'nom')
                    ->required(),
                TextInput::make('quantite_theorique')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('quantite_produite')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('quantite_rebutee')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
