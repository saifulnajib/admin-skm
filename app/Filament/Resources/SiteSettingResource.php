<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Filament\Resources\SiteSettingResource\RelationManagers;
use App\Models\SiteSetting;
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
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;


class SiteSettingResource extends Resource
{
    //protected static ?string $model = MasterPendidikan::class;
    protected static ?string $navigationLabel = 'Pengaturan Website';
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-vertical';
    protected static ?string $navigationGroup = 'Master';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getPluralModelLabel(): string
    {
        return 'Pengaturan Website';
    }

    public static function getModelLabel(): string
    {
        return 'Pengaturan Website';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Singkatan Aplikasi')->filled(),
                TextInput::make('nama_aplikasi')->filled(),
                TextInput::make('telp')->filled(),
                TextInput::make('email')->filled(),
                TextArea::make('deskripsi')->rows(5)->label('Deskripsi Aplikasi')->filled(),
                TextArea::make('tentang')->rows(5)->label('Tentang Aplikasi')->filled(),
                TextArea::make('deskripsi_unsur')->rows(5)->label('Deskripsi Unsur/Indikator')->filled(),
                TextInput::make('copyright')->filled(),
                FileUpload::make('logo')->image()->imageEditor(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')->label('#')->rowIndex(),
                TextColumn::make('name')->label('Aplikasi'),
                TextColumn::make('nama_aplikasi')->label('Deskripsi'),
                ImageColumn::make('logo')
                    ->disk('public') // pakai disk yg sesuai
                    ->height(50)     // ukuran tinggi
                    ->width(50), 
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
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
