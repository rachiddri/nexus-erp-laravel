<?php

namespace App\Filament\Resources\DocumentsSorties\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsSortiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero')
                    ->searchable(),
                TextColumn::make('type')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
                TextColumn::make('client.raison_sociale')->label('Client')
                    ->searchable(),
                TextColumn::make('bonCommande.numero_bc')->label('Bon de commande')
                    ->searchable(),
                TextColumn::make('date_sortie')
                    ->date()
                    ->sortable(),
                TextColumn::make('statut')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
                TextColumn::make('valide_par')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('valide_le')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('cree_par')
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
            ->recordActions([
                EditAction::make(),
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => route('documents-sortie.pdf', $record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
