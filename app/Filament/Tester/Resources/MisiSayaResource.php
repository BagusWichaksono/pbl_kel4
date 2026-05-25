<?php

namespace App\Filament\Tester\Resources;

use App\Filament\Tester\Resources\MisiSayaResource\Pages;
use App\Models\ApplicationTester;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MisiSayaResource extends Resource
{
    protected static ?string $model = ApplicationTester::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Misi Saya';

    protected static ?string $modelLabel = 'Misi Saya';

    protected static ?string $pluralModelLabel = 'Misi Saya';

    protected static ?string $navigationGroup = 'Aktivitas Testing';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tester_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMisiSayas::route('/'),
            'view' => Pages\ViewMisiSaya::route('/{record}'),
        ];
    }
}
