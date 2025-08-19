<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterOpdResource\Pages;
use App\Filament\Resources\MasterOpdResource\RelationManagers;
use App\Models\MasterOpd;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\CreateAction;

class MasterOpdResource extends Resource
{
    protected static ?string $model = MasterOpd::class;

    protected static ?string $navigationLabel = 'OPD';
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Master';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPluralModelLabel(): string
    {
        return 'OPD';
    }

    public static function getModelLabel(): string
    {
        return 'OPD';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id')->filled(),
                TextInput::make('name')->filled(),
                Toggle::make('is_active')->onColor('success')->offColor('danger')->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')->label('#')->rowIndex(),
                TextColumn::make('id')->label('ID')->searchable()->sortable(),
                TextColumn::make('name')->label('OPD')->searchable()->sortable(),
                TextColumn::make('is_active')->label('Status')->searchable()->sortable()->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => [
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ][$state] ?? $state),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListMasterOpds::route('/'),
            'create' => Pages\CreateMasterOpd::route('/create'),
            'edit' => Pages\EditMasterOpd::route('/{record}/edit'),
        ];
    }
}
