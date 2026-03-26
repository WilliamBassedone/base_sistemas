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
                    'label' => 'Níveis',
                    'route' => 'levels.index',
                    'icon' => 'fa-solid fa-layer-group',
                ],
                [
                    'label' => 'Menus',
                    'route' => null,
                    'url' => '#',
                    'icon' => 'fa-solid fa-bars',
                ],
                [
                    'label' => 'Grupos',
                    'route' => 'groups.index',
                    'icon' => 'fa-solid fa-layer-group',
                ],
                [
                    'label' => 'Usuários',
                    'route' => 'dashboard',
                    'icon' => 'fa-solid fa-user',
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
