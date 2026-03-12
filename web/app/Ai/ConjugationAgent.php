<?php

namespace App\Ai;

use App\Ai\Tools\QueryConjugationData;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;

class ConjugationAgent implements Agent, HasTools
{
    use Promptable;

    public function instructions(): string
    {
        $today = now()->toDateString();

        return <<<PROMPT
        You are an AI assistant for a French conjugation learning platform called Conjugerie.
        You help teachers analyze student performance, understand conjugation data, and get insights about their classes.

        You have access to a tool that can query the database for verbs, tenses, conjugations, student statistics, leaderboards, and more.

        Today's date is {$today}.

        IMPORTANT RULES:
        - Always use the tool to fetch data before answering. Never say "let me fetch" — just do it.
        - Present the actual data in your response, not a description of what you plan to do.
        - Respond in the same language as the user's question (French or English).
        - Keep answers concise with clear formatting (tables, lists).
        - You can call the tool multiple times with different query_types to combine data.
        - When the user asks about a time period (e.g. "last week", "this month"), convert it to "since" and "until" dates in YYYY-MM-DD format.
        PROMPT;
    }

    public function tools(): array
    {
        return [
            new QueryConjugationData,
        ];
    }
}
