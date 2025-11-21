@php
    // eager load relasi yang diperlukan
    $responden = $this->record->loadMissing([
        'getSurvey.getLayananOpd.getOpd',
        'getPendidikan',
        'getPekerjaan',
        'jawabanSurveys.pilihanJawaban.pertanyaan.indikator',
    ]);

    $jawabans = $responden->jawabanSurveys;
@endphp

<x-filament-panels::page>
    {{-- IDENTITAS RESPONDEN --}}
    <div class="grid gap-6 md:grid-cols-2 mb-8">
        <div class="p-4 rounded-xl border border-gray-200 bg-white dark:bg-gray-900 dark:border-gray-800">
            <h2 class="font-semibold mb-3">Data Responden</h2>
            <dl class="space-y-1 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Nama</dt>
                    <dd class="font-medium">{{ $responden->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Umur</dt>
                    <dd class="font-medium">{{ $responden->umur }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Gender</dt>
                    <dd class="font-medium text-capitalize">{{ $responden->gender }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Pendidikan</dt>
                    <dd class="font-medium">
                        {{ $responden->getPendidikan?->name ?? $responden->getPendidikan?->nama ?? '-' }}
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Pekerjaan</dt>
                    <dd class="font-medium">
                        {{ $responden->getPekerjaan?->name ?? $responden->getPekerjaan?->nama ?? '-' }}
                    </dd>
                </div>
            </dl>
        </div>

        <div class="p-4 rounded-xl border border-gray-200 bg-white dark:bg-gray-900 dark:border-gray-800">
            <h2 class="font-semibold mb-3">Informasi Survei</h2>
            <dl class="space-y-1 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Survey</dt>
                    <dd class="font-medium">
                        {{ $responden->getSurvey?->name ?? '-' }}
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">OPD</dt>
                    <dd class="font-medium">
                        {{ $responden->getSurvey?->getLayananOpd?->getOpd?->name ?? '-' }}
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Layanan OPD</dt>
                    <dd class="font-medium">
                        {{ $responden->getSurvey?->getLayananOpd?->name ?? '-' }}
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Waktu Pengisian Survey</dt>
                    <dd class="font-medium">
                        {{ $responden->created_at->locale('id')->translatedFormat('d F Y H:i:s') }}

                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Nilai IKM Responden</dt>
                    <dd class="font-bold">
                        {{ $responden->nilai ?? '-' }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- TABEL JAWABAN PER PERTANYAAN --}}
    <div class="overflow-x-auto w-full">
        <h2 class="text-base font-semibold mb-3">
            Jawaban Survei per Pertanyaan
        </h2>

        <table class="w-full text-sm text-gray-700 fi-ta-table divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800/50 fi-ta-header">
                <tr class="fi-ta-row">
                    <th class="fi-ta-cell px-4 py-3 text-left font-semibold">No</th>
                    <th class="fi-ta-cell px-4 py-3 text-left font-semibold">Unsur</th>
                    <th class="fi-ta-cell px-4 py-3 text-left font-semibold">Pertanyaan</th>
                    <th class="fi-ta-cell px-4 py-3 text-left font-semibold">Jawaban</th>
                    <th class="fi-ta-cell px-4 py-3 text-right font-semibold">Bobot</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                @forelse ($jawabans as $index => $jawaban)
                    @php
                        $pilihan = $jawaban->pilihanJawaban;
                        $pertanyaan = $pilihan?->pertanyaan;
                        $indikator = $pertanyaan?->indikator;
                        $bobot = $pilihan?->bobot;
                    @endphp
                    <tr class="fi-ta-row">
                        <td class="fi-ta-cell px-4 py-2 align-top">
                            {{ $index + 1 }}
                        </td>
                        <td class="fi-ta-cell px-4 py-2 align-top">
                            {{-- kalau kamu mau format U1, U2, dst bisa dikembangkan --}}
                            {{ $indikator?->name ?? '-' }}
                        </td>
                        <td class="fi-ta-cell px-4 py-2 align-top">
                            {{ $pertanyaan?->name ?? '-' }}
                        </td>
                        <td class="fi-ta-cell px-4 py-2 align-top">
                            {{ $pilihan?->name ?? '-' }}
                        </td>
                        <td class="fi-ta-cell px-4 py-2 text-right align-top">
                            {{ $bobot !== null ? number_format($bobot, 2, ',', '.') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr class="fi-ta-row">
                        <td class="fi-ta-cell px-4 py-4 text-center text-gray-500" colspan="5">
                            Belum ada jawaban untuk responden ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
