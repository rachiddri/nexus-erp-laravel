<?php

namespace App\Filament\Resources\InventaireLignes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventaireLigneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('inventaire_id')
                    ->relationship('inventaire', 'numero')
                    ->required(),
                Select::make('matiere_premiere_id')
                    ->relationship('matierePremiere', 'nom')
                    ->required(),
                TextInput::make('quantite_theorique')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('quantite_reelle')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('ecart')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
