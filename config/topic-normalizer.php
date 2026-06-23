<?php

declare(strict_types=1);

return [
    // Maximum number of topics returned (a repo with dozens of keywords
    // shouldn't bloat the row / UI).
    'max' => (int) env('TOPIC_NORMALIZER_MAX', 20),

    // Maximum slug length; longer values are dropped as junk.
    'max_length' => (int) env('TOPIC_NORMALIZER_MAX_LENGTH', 50),

    // Minimum slug length; shorter values are dropped as junk. 0 disables the
    // check (the default). Set to 2 to drop single-character noise.
    'min_length' => (int) env('TOPIC_NORMALIZER_MIN_LENGTH', 0),
];
