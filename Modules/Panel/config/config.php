<?php

return [
    'name' => 'Panel',
    'navigation' => [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'fa-solid fa-chart-line',
        ],
                    [
                        'label' => 'Conteúdos',
                        'route' => 'contents.index',
                        'icon' => 'fa-solid fa-file-lines',
                    ],

        [
            'label' => 'Professores',
            'route' => null,
            'url' => '#',
            'icon' => 'fa-solid fa-chalkboard-user',
        ],
        [
            'label' => 'Configurações',
            'icon' => 'fa-solid fa-gear',
            'children' => [
                [
                    'label' => 'Menus',
                    'route' => null,
                    'url' => '#',
                    'icon' => 'fa-solid fa-bars',
                ],
                [
                    'label' => 'Empresas',
                    'route' => 'companies.index',
                    'icon' => 'fa-solid fa-building',
                ],
                // [
                //     'label' => 'Níveis',
                //     'route' => 'levels.index',
                //     'icon' => 'fa-solid fa-layer-group',
                // ],
                
                [
                    'label' => 'Grupos',
                    'route' => 'groups.index',
                    'icon' => 'fa-solid fa-layer-group',
                ],
                [
                    'label' => 'Usuários',
                    'route' => 'users.index',
                    'icon' => 'fa-solid fa-user',
                ],
                                [
                    'label' => 'API Tokens',
                    'route' => 'tokens.index',
                    'icon' => 'fa-solid fa-key',
                ],
            ],
        ],
        [
            'label' => 'Desenvolvimento',
            'icon' => 'fa-solid fa-gear',
            'children' => [
                [
                    'label' => 'Componentes',
                    'route' => 'components.index',
                    'icon' => 'fa-solid fa-layer-group',
                ],
                
            ],
        ],
    ],
];
