<?php

namespace App\Filament\Resources\AvoirLignes\Schemas;

use App\Models\Produit;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Schema;

class AvoirLigneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('avoir_id')
                    ->relationship('avoir', 'numero_avoir')
                    ->required(),
                Select::make('produit_id')
                    ->relationship('produit', 'nom')
                    ->required()
                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                        if (! $state) {
                            return;
                        }
                        $produit = Produit::find($state);
                        if (! $produit) {
                            return;
                        }
                        $set('prix_unitaire', (float) $produit->prix_vente);
                        $set('designation', $produit->nom);
                        $set('montant_total', (float) ($get('quantite') ?? 0) * (float) $produit->prix_vente);
                    }),
                TextInput::make('designation')
                    ->default(null),
                TextInput::make('quantite')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Get $get, Set $set) => $set(
                        'montant_total',
                        (float) ($get('quantite') ?? 0) * (float) ($get('prix_unitaire') ?? 0)
                    )),
                TextInput::make('prix_unitaire')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Get $get, Set $set) => $set(
                        'montant_total',
                        (float) ($get('quantite') ?? 0) * (float) ($get('prix_unitaire') ?? 0)
                    )),
                TextInput::make('montant_total')
                    ->label('Montant HT')
                    ->required()
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->default(0.0),
            ]);
    }
}
