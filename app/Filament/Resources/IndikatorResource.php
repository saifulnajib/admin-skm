<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndikatorResource\Pages;
use App\Filament\Resources\IndikatorResource\RelationManagers;
use App\Models\Indikator;
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
use Filament\Forms\Components\Textarea;

class IndikatorResource extends Resource
{
    protected static ?string $model = Indikator::class;

    protected static ?string $navigationLabel = 'Indikator';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Master';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPluralModelLabel(): string
    {
        return 'Indikator';
    }

    public static function getModelLabel(): string
    {
        return 'Indikator';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->filled(),
                Textarea::make('keterangan'),
                Toggle::make('is_active')->onColor('success')->offColor('danger')->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')->label('#')->rowIndex(),
                TextColumn::make('name')->label('Indikator/Unsur')->searchable()->sortable(),
                TextColumn::make('is_active')->label('Status')->searchable()->sortable()->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => [
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ][$state] ?? $state),
                TextColumn::make('keterangan')->label('Ket.'),
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
            'index' => Pages\ListIndikators::route('/'),
            'create' => Pages\CreateIndikator::route('/create'),
            'edit' => Pages\EditIndikator::route('/{record}/edit'),
        ];
    }
}
