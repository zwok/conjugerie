<?php

return [
    // Maximum number of attempts a learner has per conjugation before the
    // correct answer is revealed and they may move to the next item.
    // Can be overridden in .env via PRACTICE_MAX_ATTEMPTS
    'max_attempts' => env('PRACTICE_MAX_ATTEMPTS', 2),
];
