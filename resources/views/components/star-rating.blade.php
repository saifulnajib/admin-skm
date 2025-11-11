@php
    $statePath = $getStatePath();
    $maxStars = $getRecord()?->pilihanJawaban?->count() ?? 5; // jumlah bintang dinamis
    $value = $getState() ?? 0;
@endphp

<div
    x-data="{
        rating: {{ $value }},
        max: {{ $maxStars }},
        setRating(star) {
            this.rating = star;
            $wire.set('{{ $statePath }}', star);
        }
    }"
    class="flex items-center space-x-2"
>
    <template x-for="star in max" :key="star">
        <svg
            @click="setRating(star)"
            :class="{
                'text-yellow-400': star <= rating,
                'text-gray-300': star > rating
            }"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            class="w-6 h-6 cursor-pointer transition-colors duration-150"
        >
            <path
                fill-rule="evenodd"
                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.38 2.458a1 1 0 00-.364 1.118l1.286 3.966c.3.921-.755 1.688-1.54 1.118L10 14.347l-3.38 2.458c-.785.57-1.84-.197-1.54-1.118l1.286-3.966a1 1 0 00-.364-1.118L2.622 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.286-3.966z"
                clip-rule="evenodd"
            />
        </svg>
    </template>

    <span class="text-sm text-gray-600" x-text="`${rating}/${max}`"></span>
</div>
