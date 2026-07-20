<?php

namespace App\Filament\Resources\Sequences\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SequenceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('prefixe')
                    ->required(),
                TextInput::make('annee')
                    ->required()
                    ->numeric()
                    ->default(2025),
                TextInput::make('dernier_numero')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
