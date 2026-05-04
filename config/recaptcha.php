<?php

return [
    'enabled' => env('RECAPTCHA_V3_ENABLED', false),
    'site_key' => env('RECAPTCHA_V3_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_V3_SECRET_KEY'),
    'verify_url' => env('RECAPTCHA_V3_VERIFY_URL', 'https://www.google.com/recaptcha/api/siteverify'),
    'min_score' => (float) env('RECAPTCHA_V3_MIN_SCORE', 0.5),
    'timeout' => (int) env('RECAPTCHA_V3_TIMEOUT', 5),
    'expected_hostname' => env('RECAPTCHA_V3_EXPECTED_HOSTNAME'),
    'actions' => [
        'login' => [
            'min_score' => (float) env('RECAPTCHA_V3_LOGIN_MIN_SCORE', 0.7),
        ],
    ],
];
