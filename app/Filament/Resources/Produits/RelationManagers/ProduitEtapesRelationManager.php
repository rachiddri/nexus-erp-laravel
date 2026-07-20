<?php

namespace App\Filament\Resources\Produits\RelationManagers;

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

class ProduitEtapesRelationManager extends RelationManager
{
    protected static string $relationship = 'produitEtapes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('etape_production_id')
                    ->relationship('etapeProduction', 'nom')
                    ->required(),
                TextInput::make('ordre')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('duree_minutes')
                    ->numeric()
                    ->default(null),
                Textarea::make('instructions')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('etape_production_id')
            ->columns([
                TextColumn::make('etapeProduction.nom')->label('Etape Production')
                    ->searchable(),
                TextColumn::make('ordre')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('duree_minutes')
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
