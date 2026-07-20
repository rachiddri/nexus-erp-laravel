<?php

namespace App\Filament\Resources\ProduitPhysiqueHistoriques\Schemas;

use Filament\Forms\Components\DateTimePicker;
use App\Enums\TypeMouvementPhysique;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProduitPhysiqueHistoriqueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('produit_physique_id')
                    ->relationship('produitPhysique', 'code_affiche')
                    ->required(),
                Select::make('type_mouvement')->options(\App\Enums\TypeMouvementPhysique::options())
                    ->required(),
                TextInput::make('etape_origine')
                    ->default(null),
                TextInput::make('etape_destination')
                    ->default(null),
                TextInput::make('emplacement_origine')
                    ->default(null),
                TextInput::make('emplacement_destination')
                    ->default(null),
                Select::make('utilisateur_id')
                    ->relationship('utilisateur', 'name')
                    ->default(null),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('date_mouvement')
                    ->required(),
            ]);
    }
}
