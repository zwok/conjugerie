<?php

namespace App\Support;

class Avatar
{
    protected const COLORS = [
        '#f97316', '#ec4899', '#8b5cf6', '#0ea5e9',
        '#14b8a6', '#eab308', '#ef4444', '#6366f1',
    ];

    public static function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name), -1, PREG_SPLIT_NO_EMPTY);
        $first = mb_substr($parts[0] ?? '?', 0, 1);
        $last = count($parts) > 1 ? mb_substr(end($parts), 0, 1) : '';

        return mb_strtoupper($first . $last);
    }

    public static function color(int $seed): string
    {
        return self::COLORS[$seed % count(self::COLORS)];
    }
}
