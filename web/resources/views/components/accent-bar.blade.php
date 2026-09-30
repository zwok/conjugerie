{{-- Accent keys for the input referenced as x-ref="answer" in the surrounding Alpine component --}}
<div {{ $attributes->merge(['class' => 'flex flex-wrap justify-center gap-1.5']) }} aria-label="Lettres accentuées">
    @foreach(['é', 'è', 'ê', 'ë', 'à', 'â', 'ç', 'î', 'ï', 'ô', 'ù', 'û', 'œ'] as $char)
        <button type="button" class="accent-key" tabindex="-1" @mousedown.prevent
                @click="window.conjugerie.insert($refs.answer, '{{ $char }}')">{{ $char }}</button>
    @endforeach
</div>
