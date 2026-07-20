<?php

namespace App\Filament\Resources\PaiementImputations;

use App\Filament\Resources\PaiementImputations\Pages\CreatePaiementImputation;
use App\Filament\Resources\PaiementImputations\Pages\EditPaiementImputation;
use App\Filament\Resources\PaiementImputations\Pages\ListPaiementImputations;
use App\Filament\Resources\PaiementImputations\Schemas\PaiementImputationForm;
use App\Filament\Resources\PaiementImputations\Tables\PaiementImputationsTable;
use App\Models\PaiementImputation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaiementImputationResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Paiements';

    protected static ?string $model = PaiementImputation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PaiementImputationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaiementImputationsTable::configure($table);
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
            'index' => ListPaiementImputations::route('/'),
            'create' => CreatePaiementImputation::route('/create'),
            'edit' => EditPaiementImputation::route('/{record}/edit'),
        ];
    }
}
