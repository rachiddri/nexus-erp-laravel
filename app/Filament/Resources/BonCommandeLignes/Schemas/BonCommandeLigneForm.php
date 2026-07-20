<?php

namespace App\Filament\Resources\BonCommandeLignes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BonCommandeLigneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('bon_commande_id')
                    ->relationship('bonCommande', 'numero_bc')
                    ->required(),
                Select::make('produit_id')
                    ->relationship('produit', 'nom')
                    ->required(),
                TextInput::make('quantite')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('prix_unitaire')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('montant_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
