<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UsersForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Compte')
                    ->description('Informations de connexion et coordonnées')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255)
                            ->helperText('Obligatoire à la création. Laisser vide pour conserver le mot de passe existant lors de l\'édition.'),
                        Toggle::make('actif')
                            ->label('Actif')
                            ->default(true)
                            ->required(),
                        TextInput::make('telephone')
                            ->label('Téléphone')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('poste')
                            ->label('Poste')
                            ->maxLength(255),
                    ]),
                Section::make('Rôles')
                    ->description('Lier l\'utilisateur à un ou plusieurs rôles existants')
                    ->schema([
                        Select::make('roles')
                            ->label('Rôles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->helperText('Sélectionnez les rôles déjà existants à associer à cet utilisateur.'),
                    ]),
            ]);
    }
}
