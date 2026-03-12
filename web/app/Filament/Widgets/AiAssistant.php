<?php

namespace App\Filament\Widgets;

use App\Ai\ConjugationAgent;
use Filament\Widgets\Widget;
use Livewire\Attributes\Validate;

class AiAssistant extends Widget
{
    protected string $view = 'filament.widgets.ai-assistant';

    protected int | string | array $columnSpan = 'full';

    #[Validate('required|string|max:500')]
    public string $question = '';

    public string $answer = '';

    public bool $loading = false;

    public function ask(): void
    {
        $this->validate();

        $this->loading = true;
        $this->answer = '';

        try {
            $response = ConjugationAgent::make()->prompt($this->question);
            $this->answer = $response->text;
        } catch (\Throwable $e) {
            $this->answer = 'Error: ' . $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }
}
