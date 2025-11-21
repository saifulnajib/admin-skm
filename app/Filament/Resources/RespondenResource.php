<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RespondenResource\Pages;
use App\Filament\Resources\RespondenResource\RelationManagers;
use App\Models\Responden;
use App\Models\MasterOpd;
use App\Models\LayananOpd;
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
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;

class RespondenResource extends Resource
{
    protected static ?string $model = Responden::class;

    

    protected static ?string $navigationLabel = 'Responden';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Survey';
    protected static ?int $navigationSort = 3; // <-- untuk urutan
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getPluralModelLabel(): string
        {
            return 'Responden';
        }

    public static function getModelLabel(): string
        {
            return 'Responden';
        }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('index')->label('#')->rowIndex(),
            TextColumn::make('name')->label('Nama')->searchable()->sortable(),
            TextColumn::make('umur')->label('Umur')->sortable(),
            TextColumn::make('gender')->label('Gender')->searchable()->sortable(),
            TextColumn::make('nilai')->label('Nilai IKM')->searchable()->sortable(),
            TextColumn::make('getPendidikan.name')
                ->label('Pendidikan')
                ->searchable()
                ->sortable(),
                TextColumn::make('getPekerjaan.name')
                ->label('Pekerjaan')
                ->searchable()
                ->sortable(),
            TextColumn::make('getSurvey.getLayananOpd.name')
                ->label('Layanan OPD')
                ->searchable()
                ->sortable(),
        ])
        ->filters([
            // Filter gender biasa
            SelectFilter::make('gender')
                ->label('Gender')
                ->options([
                    'laki-laki' => 'Laki-laki',
                    'perempuan' => 'Perempuan',
                ]),
                Selectfilter::make('id_pendidikan')->label('Pendidikan')
                ->relationship('getPendidikan', 'name'),
                SelectFilter::make('id_pekerjaan')->label('Pekerjaan')
                ->relationship('getPekerjaan', 'name'),

            // Filter nested: OPD -> Layanan
            Filter::make('opd_layanan')
                ->label('OPD & Layanan')
                ->form([
                    Select::make('id_opd')
                        ->label('OPD')
                        ->options(fn () => MasterOpd::query()
                            ->orderBy('name') // atau 'nama' kalau itu nama kolomnya
                            ->pluck('name', 'id'))
                        ->native(false)
                        ->searchable()
                        ->live(), // penting: biar saat ganti OPD, Layanan ikut update

                    Select::make('id_layanan_opd')
                        ->label('Layanan OPD')
                        ->options(function (callable $get) {
                            $opdId = $get('id_opd');

                            if (! $opdId) {
                                return [];
                            }

                            return LayananOpd::query()
                                ->where('id_opd', $opdId)
                                ->orderBy('name')
                                ->pluck('name', 'id');
                        })
                        ->native(false)
                        ->searchable()
                        ->hidden(fn (callable $get) => blank($get('id_opd'))),
                ])
                ->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if (! empty($data['id_opd'])) {
                        $namaOpd = MasterOpd::find($data['id_opd'])?->name;
                        if ($namaOpd) {
                            $indicators['id_opd'] = 'OPD: ' . $namaOpd;
                        }
                    }

                    if (! empty($data['id_layanan_opd'])) {
                        $namaLayanan = LayananOpd::find($data['id_layanan_opd'])?->name;
                        if ($namaLayanan) {
                            $indicators['id_layanan_opd'] = 'Layanan: ' . $namaLayanan;
                        }
                    }

                    return $indicators;
                })
                ->query(function (Builder $query, array $data): Builder {
                    // Filter by OPD (via getSurvey.getLayananOpd.getOpd)
                    $query->when(
                        $data['id_opd'] ?? null,
                        function (Builder $q, $opdId) {
                            $q->whereHas('getSurvey.getLayananOpd.getOpd', function ($q2) use ($opdId) {
                                $q2->where('id', $opdId);
                            });
                        }
                    );

                    // Filter by Layanan OPD (via Survey.id_layanan_opd)
                    $query->when(
                        $data['id_layanan_opd'] ?? null,
                        function (Builder $q, $layananId) {
                            $q->whereHas('getSurvey', function ($q2) use ($layananId) {
                                $q2->where('id_layanan_opd', $layananId);
                            });
                        }
                    );

                    return $query;
                }),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListRespondens::route('/'),
            'edit' => Pages\EditResponden::route('/{record}/edit'),
            'view' => Pages\ViewResponden::route('/{record}'),
        ];
    }
}
