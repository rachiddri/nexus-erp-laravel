<?php

namespace App\Filament\Resources\MouvementSoldeClients\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MouvementSoldeClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('client_id')
                    ->relationship('client', 'raison_sociale')
                    ->required(),
                TextInput::make('type_mouvement')
                    ->required(),
                TextInput::make('montant')
                    ->required()
                    ->numeric(),
                TextInput::make('solde_avant')
                    ->required()
                    ->numeric(),
                TextInput::make('solde_apres')
                    ->required()
                    ->numeric(),
                TextInput::make('reference')
                    ->default(null),
            ]);
    }
}
