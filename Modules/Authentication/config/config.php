<?php

return [
    'name' => 'Authentication',
    'root' => [
        'name' => env('AUTH_ROOT_NAME', 'Root'),
        'email' => env('AUTH_ROOT_EMAIL'),
        'password' => env('AUTH_ROOT_PASSWORD'),
    ],
    'login' => [
        'max_attempts' => (int) env('AUTH_LOGIN_MAX_ATTEMPTS', 5),
        'decay_seconds' => (int) env('AUTH_LOGIN_DECAY_SECONDS', 60),
    ],
];
