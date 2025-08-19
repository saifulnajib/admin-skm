<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LayananOpdResource\Pages;
use App\Filament\Resources\LayananOpdResource\RelationManagers;
use App\Models\LayananOpd;
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
use Filament\Tables\Filters\SelectFilter;

class LayananOpdResource extends Resource
{
    protected static ?string $model = LayananOpd::class;
    protected static ?string $navigationLabel = 'Layanan OPD';
    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static ?string $navigationGroup = 'Survey';
    protected static ?int $navigationSort = 1; // <-- untuk urutan
    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}
    public static function getPluralModelLabel(): string
        {
            return 'Layanan OPD';
        }

    public static function getModelLabel(): string
        {
            return 'Layanan OPD';
        }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->filled(),
                TextInput::make('keterangan')->filled(),
                Toggle::make('is_active')->onColor('success')->offColor('danger')->label('Status'),
                Select::make('id_opd')
                        ->label('OPD')
                        ->options(MasterOpd::all()->pluck('name', 'id'))
                        ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')->label('#')->rowIndex(),
                TextColumn::make('name')->label('Layanan')->searchable()->sortable(),
                TextColumn::make('getOpd.name')->label('OPD')->searchable()->sortable(),TextColumn::make('is_active')->label('Status')->searchable()->sortable()->badge()
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
            'index' => Pages\ListLayananOpds::route('/'),
            'create' => Pages\CreateLayananOpd::route('/create'),
            'edit' => Pages\EditLayananOpd::route('/{record}/edit'),
        ];
    }
}