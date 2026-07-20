<?php

namespace App\Filament\Resources\MouvementStockMatieres\Schemas;

use Filament\Forms\Components\Select;
use App\Enums\TypeMouvementStockMatiere;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MouvementStockMatiereForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('matiere_premiere_id')
                    ->relationship('matierePremiere', 'nom')
                    ->required(),
                Select::make('depot_id')
                    ->relationship('depot', 'nom')
                    ->required(),
                Select::make('type_mouvement')->options(\App\Enums\TypeMouvementStockMatiere::options())
                    ->required(),
                TextInput::make('quantite')
                    ->required()
                    ->numeric(),
                TextInput::make('cout_unitaire')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('reference')
                    ->default(null),
                TextInput::make('document_lie')
                    ->default(null),
                TextInput::make('cree_par')
                    ->numeric()
                    ->default(null),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
