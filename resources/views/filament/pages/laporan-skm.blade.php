<x-filament-panels::page>

    {{-- Form Filter --}}
    <div class="mb-6">
        <form wire:submit.prevent="loadData">
            {{ $this->form }}
        </form>
    </div>

    {{-- Info total responden --}}
    <div class="mb-4">
        <p class="text-sm text-gray-600">
            Total Responden:
            <span class="font-semibold">
                {{ number_format($this->totalResponden, 0, ',', '.') }}
            </span>
        </p>
    </div>

    {{-- Tabel Rekap - Filament Table Style --}}
    <div class="overflow-x-auto w-full">
        <table
            class="w-full text-sm text-gray-700 fi-ta-table divide-y divide-gray-200 dark:divide-gray-700"
        >
            <thead class="bg-gray-50 dark:bg-gray-800/50 fi-ta-header">
                <tr class="fi-ta-row">
                    <th class="fi-ta-cell px-4 py-3 text-left font-bold">
                        Karakteristik
                    </th>

                    <th class="fi-ta-cell px-4 py-3 text-left font-semibold">
                        Indikator
                    </th>

                    <th class="fi-ta-cell px-4 py-3 text-right font-semibold">
                        Jumlah
                    </th>

                    <th class="fi-ta-cell px-4 py-3 text-right font-semibold">
                        Persentase (%)
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                @php $jenis = ''; @endphp

                @forelse ($this->rows as $row)
                    <tr class="fi-ta-row">
                        {{-- Karakteristik, hanya tampil saat berubah --}}
                        <td class="fi-ta-cell px-4 py-2 align-top font-semibold">
                            {{ $row['karakteristik'] == $jenis ? '' : $row['karakteristik'] }}
                        </td>

                        {{-- Indikator --}}
                        <td class="fi-ta-cell px-4 py-2 align-top">
                            {{ $row['indikator'] }}
                        </td>

                        {{-- Jumlah --}}
                        <td class="fi-ta-cell px-4 py-2 text-right align-top">
                            {{ number_format($row['jumlah'], 0, ',', '.') }}
                        </td>

                        {{-- Persentase --}}
                        <td class="fi-ta-cell px-4 py-2 text-right align-top">
                            {{ number_format($row['persentase'], 2, ',', '.') }}
                        </td>
                    </tr>

                    @php $jenis = $row['karakteristik']; @endphp
                @empty
                    <tr class="fi-ta-row">
                        <td colspan="4" class="fi-ta-cell px-4 py-4 text-center text-gray-500">
                            Tidak ada data responden untuk filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- TABEL 2: Hasil Survey per Unsur (U1 – U9) dengan Unsur sebagai Kolom --}}
    <div class="overflow-x-auto w-full mt-8">
        <h2 class="text-base font-semibold mb-3">
            Hasil Survey per Unsur (U1 – U9)
        </h2>

        <table class="w-full text-sm text-gray-700 fi-ta-table divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800/50 fi-ta-header">
                @if (count($this->unsurRows))
                    {{-- Baris kedua header: nama lengkap unsur --}}
                    <tr class="fi-ta-row">
                        <th class="fi-ta-cell px-4 py-2 text-left text-xs text-gray-500">
                            Nama Unsur
                        </th>
                        @foreach ($this->unsurRows as $row)
                            <th class="fi-ta-cell px-4 py-2 text-xs text-gray-500 text-center">
                                {{ $row['unsur'] }}
                            </th>
                        @endforeach
                    </tr>
                @endif
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                
                {{-- Baris: Total Nilai IKM per Unsur --}}
                <tr class="fi-ta-row">
                    <td class="fi-ta-cell px-4 py-2 font-medium">
                        Total Per Unsur
                    </td>
                    @foreach ($this->unsurRows as $row)
                        <td class="fi-ta-cell px-4 py-2 text-right">
                            {{ number_format($row['totalperUnsur'], 2, ',', '.') }}
                        </td>
                    @endforeach
                </tr>

                {{-- Baris: Nilai Rata-rata --}}
                <tr class="fi-ta-row">
                    <td class="fi-ta-cell px-4 py-2 font-medium">
                        Nilai Rata-rata
                    </td>
                    @foreach ($this->unsurRows as $row)
                        <td class="fi-ta-cell px-4 py-2 text-right">
                            {{ number_format($row['rata_nilai'], 3, ',', '.') }}
                        </td>
                    @endforeach
                </tr>

                {{-- Baris: Nilai Rata-rata tertimbang --}}
                <tr class="fi-ta-row">
                    <td class="fi-ta-cell px-4 py-2 font-medium">
                        Nilai Rata-rata
                    </td>
                    @foreach ($this->unsurRows as $row)
                        <td class="fi-ta-cell px-4 py-2 text-right">
                            {{ number_format(($row['rata_nilai'] * 0.111), 3, ',', '.') }}
                        </td>
                    @endforeach
                </tr>

                {{-- Baris: Nilai IKM Unsur --}}
                <tr class="fi-ta-row">
                    <td class="fi-ta-cell px-4 py-2 font-medium">
                        Nilai IKM Unsur
                        <div class="text-xs text-gray-500">
                            (rata-rata × 25)
                        </div>
                    </td>
                    @foreach ($this->unsurRows as $row)
                        <td class="fi-ta-cell px-4 py-2 text-right">
                            {{ number_format($row['nilai_ikm'], 2, ',', '.') }}
                        </td>
                    @endforeach
                </tr>

                @if (! count($this->unsurRows))
                    <tr class="fi-ta-row">
                        <td class="fi-ta-cell px-4 py-4 text-center text-gray-500" colspan="10">
                            Belum ada data hasil per unsur untuk filter yang dipilih.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>



</x-filament-panels::page>
