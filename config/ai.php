<?php

return [
    'gemini_api_key' => env('GEMINI_API_KEY'),
    'gemini_model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),

    // Edite essas categorias para refletir seus tipos de chamados.
    'categories' => [
        'E-mail',
        'Site',
        'Servidor',
        'Pane geral',
    ],
];
