<?php

namespace App\Filament\Resources\BonCommandes\RelationManagers;

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
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BonCommandeLignesRelationManager extends RelationManager
{
    protected static string $relationship = 'bonCommandeLignes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('produit_id')
                    ->relationship('produit', 'nom')
                    ->required(),
                TextInput::make('quantite')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('prix_unitaire')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('montant_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('designation')
            ->columns([
                TextColumn::make('produit.nom')->label('Produit')
                    ->searchable(),
                TextColumn::make('quantite')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('prix_unitaire')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('montant_total')
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
