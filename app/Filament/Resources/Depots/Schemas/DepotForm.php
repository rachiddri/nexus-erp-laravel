<?php

namespace App\Filament\Resources\Depots\Schemas;

use Filament\Forms\Components\TextInput;
use App\Enums\TypeDepot;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DepotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Select::make('type')->options(\App\Enums\TypeDepot::options())
                    ->required()
                    ->default('produit_fini'),
                TextInput::make('adresse')
                    ->default(null),
                Toggle::make('actif')
                    ->required(),
            ]);
    }
}
