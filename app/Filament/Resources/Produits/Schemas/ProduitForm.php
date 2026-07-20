<?php

namespace App\Filament\Resources\Produits\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProduitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->required(),
                TextInput::make('reference')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('categorie')
                    ->default(null),
                TextInput::make('prix_vente')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tva_taux')
                    ->required()
                    ->numeric()
                    ->default(19.0),
                TextInput::make('stock_alerte_min')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('gamme')
                    ->default(null),
                TextInput::make('longueur')
                    ->numeric()
                    ->default(null),
                TextInput::make('largeur')
                    ->numeric()
                    ->default(null),
                TextInput::make('hauteur')
                    ->numeric()
                    ->default(null),
                TextInput::make('poids')
                    ->numeric()
                    ->default(null),
                FileUpload::make('image_principale')
                    ->image(),
                Textarea::make('fiches_techniques')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('notes_production')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('actif')
                    ->required(),
            ]);
    }
}
