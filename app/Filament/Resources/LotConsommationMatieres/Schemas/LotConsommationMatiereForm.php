<?php

namespace App\Filament\Resources\LotConsommationMatieres\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LotConsommationMatiereForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lot_produit_id')
                    ->relationship('lotProduit', 'id')
                    ->required(),
                Select::make('matiere_premiere_id')
                    ->relationship('matierePremiere', 'nom')
                    ->required(),
                TextInput::make('quantite_consommee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('quantite_rebutee')
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
