<?php

return [
    // keeping this simple for assignment scope
    'code_length' => (int) env('SHORT_CODE_LENGTH', 8),
    'max_generate_attempts' => (int) env('SHORT_CODE_MAX_ATTEMPTS', 10),
];
