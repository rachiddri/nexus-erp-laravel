<?php

namespace App\Filament\Resources\LotProduits\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DefautsProductionsRelationManager extends RelationManager
{
    protected static string $relationship = 'defautsProductions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('etape_production_id')
                    ->relationship('etapeProduction', 'nom')
                    ->required(),
                TextInput::make('type_defaut')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('gravite')
                    ->required()
                    ->default('mineur'),
                TextInput::make('quantite_impactee')
                    ->required()
                    ->numeric()
                    ->default(1),
                Textarea::make('cause_racine')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('action_immediate')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('decision')
                    ->default(null),
                TextInput::make('statut')
                    ->required()
                    ->default('ouvert'),
                TextInput::make('signale_par')
                    ->numeric()
                    ->default(null),
                TextInput::make('resolu_par')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('date_resolution'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type_defaut')
            ->columns([
                TextColumn::make('etapeProduction.nom')->label('Etape Production')
                    ->searchable(),
                TextColumn::make('type_defaut')
                    ->searchable(),
                TextColumn::make('gravite')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
                TextColumn::make('quantite_impactee')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('decision')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
                TextColumn::make('statut')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
                TextColumn::make('signale_par')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('resolu_par')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date_resolution')
                    ->dateTime()
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
