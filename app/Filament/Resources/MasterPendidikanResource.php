<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterPendidikanResource\Pages;
use App\Filament\Resources\MasterPendidikanResource\RelationManagers;
use App\Models\MasterPendidikan;
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

class MasterPendidikanResource extends Resource
{
    protected static ?string $model = MasterPendidikan::class;
    protected static ?string $navigationLabel = 'Pendidikan';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Master';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getPluralModelLabel(): string
    {
        return 'Pendidikan';
    }

    public static function getModelLabel(): string
    {
        return 'Pendidikan';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->filled(),
                TextInput::make('singkatan')->filled(),
                Toggle::make('is_active')->onColor('success')->offColor('danger')->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')->label('#')->rowIndex(),
                TextColumn::make('name')->label('Pendidikan')->searchable()->sortable(),
                TextColumn::make('singkatan')->label('Singkatan')->searchable()->sortable(),
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
            'index' => Pages\ListMasterPendidikans::route('/'),
            'create' => Pages\CreateMasterPendidikan::route('/create'),
            'edit' => Pages\EditMasterPendidikan::route('/{record}/edit'),
        ];
    }
}
