<?php

namespace App\Filament\Resources\DefautsProductions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DefautsProductionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lotProduit.id')->label('Lot produit')->label('Lot produit')
                    ->searchable(),
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
