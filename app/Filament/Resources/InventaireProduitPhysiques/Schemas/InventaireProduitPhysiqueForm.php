<?php

namespace App\Filament\Resources\InventaireProduitPhysiques\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventaireProduitPhysiqueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('inventaire_id')
                    ->relationship('inventaire', 'numero')
                    ->required(),
                Select::make('produit_physique_id')
                    ->relationship('produitPhysique', 'code_affiche')
                    ->required(),
                TextInput::make('statut')
                    ->required()
                    ->default('present'),
            ]);
    }
}
