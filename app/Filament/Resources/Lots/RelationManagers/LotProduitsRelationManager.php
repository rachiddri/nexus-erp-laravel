<?php

namespace App\Filament\Resources\Lots\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LotProduitsRelationManager extends RelationManager
{
    protected static string $relationship = 'lotProduits';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('ordre_production_ligne_id')
                    ->relationship('ordreProductionLigne', 'id')
                    ->required(),
                Select::make('produit_id')
                    ->relationship('produit', 'nom')
                    ->required(),
                TextInput::make('quantite_theorique')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('quantite_produite')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('quantite_rebutee')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('produit_id')
            ->columns([
                TextColumn::make('ordreProductionLigne.id')->label('Ordre Production Ligne')
                    ->searchable(),
                TextColumn::make('produit.nom')->label('Produit')
                    ->searchable(),
                TextColumn::make('quantite_theorique')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quantite_produite')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quantite_rebutee')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
