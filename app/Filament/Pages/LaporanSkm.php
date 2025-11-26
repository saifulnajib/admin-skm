<?php

namespace App\Filament\Pages;

use App\Models\MasterOpd;
use App\Models\LayananOpd;
use App\Models\MasterPendidikan;
use App\Models\MasterPekerjaan;
use App\Models\Responden;
use App\Models\JawabanSurvey;
use App\Models\Indikator;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;

class LaporanSkm extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan SKM';
    protected static ?string $navigationGroup = 'Survey';
    protected static ?int $navigationSort     = 4; // <-- untuk urutan
    protected static ?string $title           = 'Laporan SKM';
    protected static string  $view            = 'filament.pages.laporan-skm';

    public ?int $id_opd         = null;
    public ?int $id_layanan_opd = null;

    public array $rows = [];
    public array $unsurRows = [];
    public int $totalResponden = 0;
    public float $totalNilaiIKM = 0;

    public function mount(): void
    {
        $this->form->fill();
        $this->loadData();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_opd')
                    ->label('OPD')
                    ->options(fn () => MasterOpd::query()
                        ->orderBy('name')
                        ->pluck('name', 'id'))
                    ->native(false)
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->id_opd = $state;
                        $this->id_layanan_opd = null;
                        $this->loadData(); // <-- unsur + karakteristik sama-sama terupdate
                    }),

                Select::make('id_layanan_opd')
                    ->label('Layanan OPD')
                    ->options(function (callable $get) {
                        $opdId = $get('id_opd') ?? $this->id_opd;

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
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->id_layanan_opd = $state;
                        $this->loadData(); 
                    }),
            ])
            ->columns(2);
    }

    public function loadData(): void
    {
        // Query dasar responden
        $query = Responden::query()
            ->with(['getSurvey.getLayananOpd', 'getPendidikan', 'getPekerjaan']);

        // Filter: Layanan dulu (langsung ke Survey.id_layanan_opd)
        if ($this->id_layanan_opd) {
            $layananId = $this->id_layanan_opd;

            $query->whereHas('getSurvey', function ($q) use ($layananId) {
                $q->where('id_layanan_opd', $layananId);
            });
        }

        // Filter: OPD via LayananOpd.id_opd
        if ($this->id_opd) {
            $opdId = $this->id_opd;

            $query->whereHas('getSurvey.getLayananOpd', function ($q) use ($opdId) {
                $q->where('id_opd', $opdId);
            });
        }

        // Optional: hanya responden aktif
        // $query->where('is_active', 1);

        // Clone untuk dipakai di buildUnsurRows supaya filter sama persis
        $filteredQuery = clone $query;

        $this->totalResponden = $query->count();
        $rows = [];

        if ($this->totalResponden === 0) {
            $this->rows = [];
            $this->unsurRows = [];
            $this->totalNilaiIKM = 0;
            return;
        }

        // =========================
        // 1. REKAP JENIS KELAMIN
        // =========================

        $genderMap = [
            'laki-laki' => 'Laki-laki',
            'perempuan' => 'Perempuan',
        ];

        foreach ($genderMap as $value => $label) {
            $jumlah = (clone $query)->where('gender', $value)->count();

            if ($jumlah === 0) {
                continue;
            }

            $rows[] = [
                'karakteristik' => 'Jenis Kelamin',
                'indikator'     => $label,
                'jumlah'        => $jumlah,
                'persentase'    => $this->persen($jumlah, $this->totalResponden),
            ];
        }

        $jumlahLain = (clone $query)
            ->whereNotIn('gender', array_keys($genderMap))
            ->orWhereNull('gender')
            ->count();

        if ($jumlahLain > 0) {
            $rows[] = [
                'karakteristik' => 'Jenis Kelamin',
                'indikator'     => 'Lainnya / Tidak diisi',
                'jumlah'        => $jumlahLain,
                'persentase'    => $this->persen($jumlahLain, $this->totalResponden),
            ];
        }

        // =========================
        // 2. REKAP PENDIDIKAN
        // =========================

        $pendidikans = MasterPendidikan::query()
            ->orderBy('id')
            ->get();

        foreach ($pendidikans as $pendidikan) {
            $jumlah = (clone $query)
                ->where('id_pendidikan', $pendidikan->id)
                ->count();

            if ($jumlah === 0) {
                continue;
            }

            $label = $pendidikan->name ?? $pendidikan->name ?? ('ID ' . $pendidikan->id);

            $rows[] = [
                'karakteristik' => 'Pendidikan',
                'indikator'     => $label,
                'jumlah'        => $jumlah,
                'persentase'    => $this->persen($jumlah, $this->totalResponden),
            ];
        }

        // =========================
        // 3. REKAP PEKERJAAN
        // =========================

        $pekerjaans = MasterPekerjaan::query()
            ->orderBy('id')
            ->get();

        foreach ($pekerjaans as $pekerjaan) {
            $jumlah = (clone $query)
                ->where('id_pekerjaan', $pekerjaan->id)
                ->count();

            if ($jumlah === 0) {
                continue;
            }

            $label = $pekerjaan->name ?? $pekerjaan->name ?? ('ID ' . $pekerjaan->id);

            $rows[] = [
                'karakteristik' => 'Pekerjaan',
                'indikator'     => $label,
                'jumlah'        => $jumlah,
                'persentase'    => $this->persen($jumlah, $this->totalResponden),
            ];
        }

        $this->rows = $rows;

        // =========================
        // 4. REKAP UNSUR U1–U9
        // =========================

        $this->unsurRows = $this->buildUnsurRows($filteredQuery);
    }

    protected function buildUnsurRows($baseRespondenQuery): array
{
    // Ambil ID responden yang sudah terfilter (OPD, Layanan, dst.)
    $respondenIds = (clone $baseRespondenQuery)->pluck('id');

    if ($respondenIds->isEmpty()) {
        $this->totalNilaiIKM = 0;
        return [];
    }

    $rows = [];

    // Ambil semua indikator aktif, urut berdasar id
    $indikators = Indikator::query()
        ->where('is_active', 1)
        ->orderBy('id')
        ->get();

    $i = 1; // U1, U2, dst.

    // akumulator untuk IKM total
    $totalBobotSemuaUnsur = 0; // Σ (rata × jumlah)
    $totalJawabanSemuaUnsur = 0; // Σ jumlah

    foreach ($indikators as $indikator) {
        $data = JawabanSurvey::query()
            ->whereIn('jawaban_survey.id_responden', $respondenIds)
            // pertanyaan yang indikatornya = indikator ini
            ->whereHas('pilihanJawaban.pertanyaan', function ($q) use ($indikator) {
                $q->where('id_indikator', $indikator->id);
            })
            // nilai diambil dari pilihan_jawaban.bobot
            ->join('pilihan_jawaban', 'pilihan_jawaban.id', '=', 'jawaban_survey.id_pilihan_jawaban')
            ->selectRaw('COUNT(*) as jumlah, AVG(pilihan_jawaban.bobot) as rata_bobot')
            ->first();

        if (! $data || $data->jumlah == 0) {
            $i++;
            continue;
        }

        $rata        = (float) $data->rata_bobot;
        $jumlah      = (int) $data->jumlah;
        $ikmUnsur    = round($rata * 25, 2);
        $totalperUnsur = $rata * $jumlah;

        // akumulasi untuk IKM total
        $totalBobotSemuaUnsur   += $totalperUnsur;
        $totalJawabanSemuaUnsur += $jumlah;

        $rows[] = [
            'kode'          => 'U' . $i,
            'unsur'         => 'U' . $i . ' - ' . $indikator->name,
            'jumlah'        => $jumlah,
            'rata_nilai'    => round($rata, 3),
            'nilai_ikm'     => $ikmUnsur,
            'totalperUnsur' => $totalperUnsur,
        ];

        $i++;
    }

    // hitung IKM total
    if ($totalJawabanSemuaUnsur > 0) {
        $rataSemuaUnsur      = $totalBobotSemuaUnsur / $totalJawabanSemuaUnsur;
        $this->totalNilaiIKM = round($rataSemuaUnsur * 25, 2);
    } else {
        $this->totalNilaiIKM = 0;
    }

    return $rows;
}


    protected function persen(int|float $jumlah, int $total): float
    {
        if ($total === 0) {
            return 0;
        }

        return round(($jumlah / $total) * 100, 2);
    }
}
