<?php

namespace App\Filament\Resources\MouvementSoldeClients;

use App\Filament\Resources\MouvementSoldeClients\Pages\CreateMouvementSoldeClient;
use App\Filament\Resources\MouvementSoldeClients\Pages\EditMouvementSoldeClient;
use App\Filament\Resources\MouvementSoldeClients\Pages\ListMouvementSoldeClients;
use App\Filament\Resources\MouvementSoldeClients\Schemas\MouvementSoldeClientForm;
use App\Filament\Resources\MouvementSoldeClients\Tables\MouvementSoldeClientsTable;
use App\Models\MouvementSoldeClient;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MouvementSoldeClientResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Commercial';

    protected static ?string $model = MouvementSoldeClient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return MouvementSoldeClientForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MouvementSoldeClientsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMouvementSoldeClients::route('/'),
            'create' => CreateMouvementSoldeClient::route('/create'),
            'edit' => EditMouvementSoldeClient::route('/{record}/edit'),
        ];
    }
}
