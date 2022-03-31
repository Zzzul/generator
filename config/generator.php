<?php

return [
    'data_types' => [
        'string',
        'integer',
        'text',
        'bigInteger',
        'boolean',
        'char',
        'date',
        'time',
        'dateTime',
        'decimal',
        'double',
        'enum',
        'float',
        'foreignId',
        'tinyInteger',
        'tinyText',
        'longText'
    ],
    'sidebars' => [
        [
            'header' => 'Main',
            'menus' => [
                [
                    'title' => 'Dashboard',
                    'icon' => '<i class="bi bi-speedometer"></i>',
                    'route' => '/',
                    'sub_menus' => [],
                ],
                [
                    'title' => 'Main Data',
                    'icon' => '<i class="bi bi-collection-fill"></i>',
                    'route' => null,
                    'sub_menus' => [
                        [
                            'title' => 'Products',
                            'route' => '/products'
                        ]
                    ],
                ],

            ],
        ],
        [
            'header' => 'Users',
            'menus' => [
                [
                    'title' => 'Users',
                    'icon' => '<i class="bi bi-people-fill"></i>',
                    'route' => '/users',
                    'sub_menus' => [],
                ],
                [
                    'title' => 'Roles & Permissions',
                    'icon' => '<i class="bi bi-person-check-fill"></i>',
                    'route' => '/roles',
                    'sub_menus' => [],
                ]
            ],
        ],
        [
            'header' => 'Generators',
            'menus' => [
                [
                    'title' => 'CRUD Generator',
                    'icon' => '<i class="bi bi-grid-fill"></i>',
                    'route' => '/generators/create',
                    'sub_menus' => [],
                ],
            ],
        ],
    ],
];
