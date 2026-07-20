<?php

namespace App\Filament\Resources\ProduitPhysiques\Schemas;

use Filament\Forms\Components\DateTimePicker;
use App\Enums\StatutProduitPhysique;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProduitPhysiqueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code_affiche')
                    ->required(),
                Select::make('produit_id')
                    ->relationship('produit', 'nom')
                    ->required(),
                Select::make('lot_id')
                    ->relationship('lot', 'numero_lot')
                    ->required(),
                Select::make('lot_produit_id')
                    ->relationship('lotProduit', 'id')
                    ->default(null),
                Select::make('etape_actuelle_id')
                    ->relationship('etapeActuelle', 'nom')
                    ->default(null),
                Select::make('emplacement_id')
                    ->relationship('emplacement', 'code_emplacement')
                    ->default(null),
                Select::make('statut')->options(\App\Enums\StatutProduitPhysique::options())
                    ->required()
                    ->default('en_production'),
                DateTimePicker::make('date_creation')
                    ->required(),
                DateTimePicker::make('date_sortie'),
                TextInput::make('cree_par')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
