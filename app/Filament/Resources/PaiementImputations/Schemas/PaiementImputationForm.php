<?php

namespace App\Filament\Resources\PaiementImputations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaiementImputationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('paiement_id')
                    ->relationship('paiement', 'numero')
                    ->required(),
                Select::make('facture_id')
                    ->relationship('facture', 'numero_facture')
                    ->required(),
                TextInput::make('montant')
                    ->required()
                    ->numeric(),
            ]);
    }
}
