<?php

namespace App\Filament\Resources\BonTransfertLignes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BonTransfertLigneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('bon_transfert_id')
                    ->relationship('bonTransfert', 'numero')
                    ->required(),
                Select::make('produit_physique_id')
                    ->relationship('produitPhysique', 'code_affiche')
                    ->required(),
                TextInput::make('code_affiche')
                    ->default(null),
            ]);
    }
}
