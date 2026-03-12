<?php

namespace App\Filament\Widgets;

use App\Ai\ConjugationAgent;
use Filament\Widgets\Widget;
use Laravel\Ai\Streaming\Events\StreamEvent;
use Laravel\Ai\Streaming\Events\TextDelta;
use Livewire\Attributes\Validate;

class AiAssistant extends Widget
{
    protected string $view = 'filament.widgets.ai-assistant';

    protected int | string | array $columnSpan = 'full';

    #[Validate('required|string|max:500')]
    public string $question = '';

    public array $messages = [];

    public string $streamedText = '';

    public bool $loading = false;

    public function ask(): void
    {
        $this->validate();

        $this->messages[] = ['role' => 'user', 'content' => $this->question];
        $this->streamedText = '';
        $this->loading = true;
        $prompt = $this->question;
        $this->question = '';

        try {
            $response = ConjugationAgent::make()
                ->withMessages($this->messages)
                ->stream($prompt);

            $response->each(function (StreamEvent $event) {
                if ($event instanceof TextDelta) {
                    $this->streamedText .= $event->delta;
                    $this->stream('streamedResponse', $event->delta);
                }
            });

            $this->messages[] = ['role' => 'assistant', 'content' => $this->streamedText];
            $this->streamedText = '';
        } catch (\Throwable $e) {
            $this->messages[] = ['role' => 'assistant', 'content' => 'Error: ' . $e->getMessage()];
        } finally {
            $this->loading = false;
        }
    }

    public function clearChat(): void
    {
        $this->messages = [];
        $this->streamedText = '';
    }
}
