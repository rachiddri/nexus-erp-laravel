<?php

namespace App\Filament\Resources\DocumentSortieLignes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DocumentSortieLigneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('document_sortie_id')
                    ->relationship('documentSortie', 'id')
                    ->required(),
                Select::make('produit_physique_id')
                    ->relationship('produitPhysique', 'code_affiche')
                    ->required(),
                TextInput::make('numero_lot')
                    ->default(null),
            ]);
    }
}
