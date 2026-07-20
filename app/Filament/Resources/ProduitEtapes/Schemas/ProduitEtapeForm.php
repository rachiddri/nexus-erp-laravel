<?php

namespace App\Filament\Resources\ProduitEtapes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProduitEtapeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('produit_id')
                    ->relationship('produit', 'nom')
                    ->required(),
                Select::make('etape_production_id')
                    ->relationship('etapeProduction', 'nom')
                    ->required(),
                TextInput::make('ordre')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('duree_minutes')
                    ->numeric()
                    ->default(null),
                Textarea::make('instructions')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
