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
            'label' => 'Cadastros',
            'icon' => 'fa-solid fa-address-book',
            'children' => [
                [
                    'label' => 'Empresas',
                    'route' => 'companies.index',
                    'icon' => 'fa-solid fa-building',
                ],
                [
                    'label' => 'Clientes',
                    'route' => null,
                    'url' => '#',
                    'icon' => 'fa-solid fa-handshake',
                ],
                [
                    'label' => 'Lojas',
                    'route' => null,
                    'url' => '#',
                    'icon' => 'fa-solid fa-store',
                ],
                [
                    'label' => 'Professores',
                    'route' => null,
                    'url' => '#',
                    'icon' => 'fa-solid fa-chalkboard-user',
                ],
            ],
        ],
        [
            'label' => 'Operacional',
            'icon' => 'fa-solid fa-diagram-project',
            'children' => [
                [
                    'label' => 'Conteúdos',
                    'route' => 'contents.index',
                    'icon' => 'fa-solid fa-file-lines',
                ],
                [
                    'label' => 'Formulários',
                    'route' => null,
                    'url' => '#',
                    'icon' => 'fa-regular fa-rectangle-list',
                ],
            ],
        ],
        [
            'label' => 'Relatórios',
            'icon' => 'fa-solid fa-chart-column',
            'children' => [
                [
                    'label' => 'Participantes',
                    'route' => null,
                    'url' => '#',
                    'icon' => 'fa-solid fa-users',
                ],
                [
                    'label' => 'Erros',
                    'route' => null,
                    'url' => '#',
                    'icon' => 'fa-solid fa-bug',
                ],
                [
                    'label' => 'Notas Fiscais',
                    'route' => null,
                    'url' => '#',
                    'icon' => 'fa-regular fa-file-lines',
                ],
                [
                    'label' => 'Números',
                    'route' => null,
                    'url' => '#',
                    'icon' => 'fa-solid fa-list-ol',
                ],
            ],
        ],
        [
            'label' => 'Administrativo',
            'icon' => 'fa-solid fa-user-shield',
            'children' => [
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
