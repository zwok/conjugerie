<x-filament-widgets::widget>
    <x-filament::section heading="AI Assistant" icon="heroicon-o-sparkles">
        <div class="space-y-4">
            {{-- Chat history --}}
            @if(count($messages) > 0 || $loading)
                <div class="space-y-3 max-h-96 overflow-y-auto" id="ai-chat-messages">
                    @foreach($messages as $message)
                        <div @class([
                            'p-3 rounded-lg text-sm',
                            'bg-primary-50 dark:bg-primary-900/20 ml-8' => $message['role'] === 'user',
                            'bg-gray-50 dark:bg-gray-800 mr-8 prose dark:prose-invert max-w-none' => $message['role'] === 'assistant',
                        ])>
                            <div class="text-xs font-medium mb-1 opacity-60">
                                {{ $message['role'] === 'user' ? 'Vous' : 'AI' }}
                            </div>
                            @if($message['role'] === 'assistant')
                                {!! \Illuminate\Support\Str::markdown($message['content']) !!}
                            @else
                                {{ $message['content'] }}
                            @endif
                        </div>
                    @endforeach

                    {{-- Streaming response --}}
                    @if($loading)
                        <div class="p-3 rounded-lg text-sm bg-gray-50 dark:bg-gray-800 mr-8 prose dark:prose-invert max-w-none">
                            <div class="text-xs font-medium mb-1 opacity-60">AI</div>
                            <div wire:stream="streamedResponse">{!! \Illuminate\Support\Str::markdown($streamedText) !!}</div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Input --}}
            <form wire:submit="ask" class="flex gap-2">
                <div class="flex-1">
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="text"
                            wire:model="question"
                            placeholder="Posez une question sur vos données..."
                            :disabled="$loading"
                        />
                    </x-filament::input.wrapper>
                </div>

                <x-filament::button type="submit" :disabled="$loading">
                    <span wire:loading.remove wire:target="ask">Envoyer</span>
                    <span wire:loading wire:target="ask">
                        <x-filament::loading-indicator class="h-4 w-4" />
                    </span>
                </x-filament::button>

                @if(count($messages) > 0)
                    <x-filament::button color="gray" wire:click="clearChat" :disabled="$loading">
                        Effacer
                    </x-filament::button>
                @endif
            </form>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
