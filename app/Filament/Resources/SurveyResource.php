<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\RelationManagers;
use App\Models\LayananOpd;
use App\Models\Survey;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationLabel = 'Survey';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Survey';
    protected static ?int $navigationSort = 1; // <-- untuk urutan
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getPluralModelLabel(): string
        {
            return 'Survey';
        }

    public static function getModelLabel(): string
        {
            return 'Survey';
        }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->filled(),
                TextInput::make('keterangan')->filled(),
                Toggle::make('is_active')->onColor('success')->offColor('danger')->label('Status'),
                Select::make('id_layanan_opd')
                        ->label('Layanan')
                        ->options(LayananOpd::all()->pluck('name', 'id'))
                        ->placeholder('Pilih layanan...')
                        ->searchable(),
                Forms\Components\Fieldset::make('Periode')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    DatePicker::make('start_date')
                                                ->label('Mulai')
                                                ->format('Y-m-d')
                                                ->native(false)
                                                ->displayFormat('d/m/Y')
                                                ->closeOnDateSelection(),
                                    DatePicker::make('end_date')
                                                ->label('Selesai')
                                                ->format('Y-m-d')
                                                ->native(false)
                                                ->displayFormat('d/m/Y')
                                                ->closeOnDateSelection()
                                                ->after('start_date'),
                                ]),
                            ]),
                Forms\Components\Grid::make(1)
                    ->schema([
                        Repeater::make('pertanyaan')
                            ->relationship('pertanyaan')
                            ->addActionLabel('Tambah Indikator/Pertanyaan')
                            ->schema([
                                Select::make('id_indikator')
                                    ->label('Indikator')
                                    ->options(Indikator::all()->pluck('name', 'id'))
                                    ->placeholder('Pilih Indikator...')
                                    ->required(),
                                TextInput::make('name')->label('Pertanyaan')->required(),
                                Repeater::make('pilihanJawaban')
                                    ->relationship('pilihanJawaban')
                                    ->schema([
                                        TextInput::make('name')->label('Jawaban')->required(),
                                        TextInput::make('bobot')->label('Bobot')->required()->numeric(),
                                    ])
                                    ->addActionLabel('Tambah Pilihan Jawaban')
                                    ->grid(4)
                                    ->defaultItems(4)
                                
                            ]), 
                    ]), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')->label('#')->rowIndex(),
                TextColumn::make('name')->label('Survey')->searchable()->sortable(),
               // TextColumn::make('getOpd.name')->label('OPD')->searchable()->sortable(),
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
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }
}
