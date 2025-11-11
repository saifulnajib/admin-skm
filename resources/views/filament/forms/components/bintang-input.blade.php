@php
    // Variabel $viewData adalah array yang diteruskan dari PHP, jadi $viewData['statePath'] aman.
    $statePath = $viewData['statePath']; 
    $maxBintang = 5;
    
    // Gunakan $wire.$entangle() untuk mengikat state Alpine ke state Livewire/Filament.
    $entangleExpression = '$wire.$entangle(\'' . $statePath . '\')';
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :helper-text="'Klik bintang untuk memberi bobot nilai (1 sampai 5)'"
>
    {{-- Inisialisasi komponen Alpine.js --}}
    <div x-data="{ 
        // Binding state Alpine ke state Livewire/Filament
        state: {{ $entangleExpression }}, 
        rating: null,
        maxRating: {{ $maxBintang }},
        
        // Fungsi untuk menginisialisasi rating saat komponen dimuat
        init() {
            // Inisialisasi nilai rating dari state Livewire (atau default 1 jika kosong)
            this.rating = this.state ?? 1; 
        },

        // Fungsi yang dipanggil saat bintang diklik
        setRating(newRating) {
            this.state = newRating; // Memperbarui state Livewire/Filament (menyimpan nilai)
            this.rating = newRating; // Memperbarui tampilan Alpine lokal
        }
    }" 
    x-init="init()"
    class="filament-forms-star-rating">
        
        <div class="flex items-center space-x-1">
            @for ($i = 1; $i <= $maxBintang; $i++)
                <span
                    x-on:click="setRating({{ $i }})"
                    x-bind:class="{ 
                        // Mengubah warna bintang berdasarkan nilai rating
                        'text-yellow-500 hover:text-yellow-600': {{ $i }} <= rating, 
                        'text-gray-300 hover:text-gray-400': {{ $i }} > rating 
                    }"
                    class="cursor-pointer transition duration-150 ease-in-out"
                    title="{{ $i }} Bintang"
                >
                    <x-heroicon-s-star class="w-6 h-6" />
                </span>
            @endfor
            
            {{-- Tampilkan nilai rating saat ini --}}
            <span x-text="'(' + rating + ')'" class="ml-2 text-sm text-gray-500"></span>
        </div>
    </div>
</x-dynamic-component>
