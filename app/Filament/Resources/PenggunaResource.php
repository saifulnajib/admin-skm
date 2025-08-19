<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggunaResource\Pages;
use App\Filament\Resources\PenggunaResource\RelationManagers;
use App\Models\User;
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

class PenggunaResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 1; // <-- untuk urutan
    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}
    public static function form(Form $form): Form
    {
        return $form
            ->schema([      
                TextInput::make('name')->filled(),
                TextInput::make('email')->filled()->unique(ignoreRecord: true),
                TextInput::make('password')->password()->confirmed()->nullable()->required(fn (string $context) => $context === 'create')->dehydrated(fn ($state) => filled($state)),
                TextInput::make('password_confirmation')->password()->nullable()->required(fn (string $context) => $context === 'create'),
                Toggle::make('is_active')->onColor('success')->offColor('danger')->label('Status'),
                Select::make('roles')
                    ->relationship('roles','name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->filled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('index')->label('#')->rowIndex(),
            TextColumn::make('name')->label('Name')->searchable()->sortable(),
            TextColumn::make('roles.name')->label('Role')->searchable()->sortable(),
            TextColumn::make('email')->label('Email')->searchable()->sortable(),
            TextColumn::make('created_at')->label('Created')->dateTime(),
            ])
            ->filters([
               SelectFilter::make('roles.name')
                     ->relationship('roles', 'name')
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
            'index' => Pages\ListPenggunas::route('/'),
            'create' => Pages\CreatePengguna::route('/create'),
            'edit' => Pages\EditPengguna::route('/{record}/edit'),
        ];
    }
}
