<x-filament-widgets::widget>
    <x-filament::section heading="AI Assistant" icon="heroicon-o-sparkles">
        <form wire:submit="ask" class="space-y-4">
            <x-filament::input.wrapper>
                <x-filament::input
                    type="text"
                    wire:model="question"
                    placeholder="Posez une question sur vos données..."
                    :disabled="$loading"
                />
            </x-filament::input.wrapper>

            <x-filament::button type="submit" :disabled="$loading" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="ask">Demander</span>
                <span wire:loading wire:target="ask">Chargement...</span>
            </x-filament::button>
        </form>

        @if($answer)
            <div class="mt-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-800 prose dark:prose-invert max-w-none text-sm">
                {!! \Illuminate\Support\Str::markdown($answer) !!}
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>