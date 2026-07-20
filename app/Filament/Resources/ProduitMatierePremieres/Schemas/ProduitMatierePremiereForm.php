<?php

namespace App\Filament\Resources\ProduitMatierePremieres\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProduitMatierePremiereForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('produit_id')
                    ->relationship('produit', 'nom')
                    ->required(),
                Select::make('matiere_premiere_id')
                    ->relationship('matierePremiere', 'nom')
                    ->required(),
                TextInput::make('quantite')
                    ->required()
                    ->numeric(),
                TextInput::make('rebut')
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
