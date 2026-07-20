<?php

namespace App\Filament\Resources\BonCommandeLignes\Schemas;

use App\Models\Produit;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
                        $set('description', $produit->nom);
                        $set('montant_total', (float) ($get('quantite') ?? 0) * (float) $produit->prix_vente);
                    }),
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
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
