<?php

return [
    // Maximum number of attempts a learner has per conjugation before the
    // correct answer is revealed and they may move to the next item.
    // Can be overridden in .env via PRACTICE_MAX_ATTEMPTS
    'max_attempts' => env('PRACTICE_MAX_ATTEMPTS', 2),

    // Number of questions in one practice round
    'round_size' => env('PRACTICE_ROUND_SIZE', 10),

    // XP awarded for a correct answer on the first try / on a later try
    'xp_first_try' => env('PRACTICE_XP_FIRST_TRY', 15),
    'xp_retry' => env('PRACTICE_XP_RETRY', 10),

    // XP a learner should aim for each week (shown on the profile page)
    'weekly_goal_xp' => env('PRACTICE_WEEKLY_GOAL_XP', 500),
];
