<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('raison_sociale')
                    ->required(),
                TextInput::make('nif')
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                TextInput::make('tel')
                    ->tel()
                    ->default(null),
                Textarea::make('adresse')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('plafond_credit')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('solde')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('actif')
                    ->required(),
            ]);
    }
}
