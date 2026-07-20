<?php

namespace App\Filament\Resources\RetourClientLignes\Schemas;

use Filament\Forms\Components\Select;
use App\Enums\EtatProduit;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RetourClientLigneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('retour_client_id')
                    ->relationship('retourClient', 'id')
                    ->required(),
                Select::make('produit_physique_id')
                    ->relationship('produitPhysique', 'code_affiche')
                    ->required(),
                Select::make('produit_id')
                    ->relationship('produit', 'nom')
                    ->required(),
                TextInput::make('quantite')
                    ->required()
                    ->numeric()
                    ->default(1),
                Textarea::make('motif')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('etat_produit')->options(\App\Enums\EtatProduit::options())
                    ->required()
                    ->default('defectueux'),
            ]);
    }
}
