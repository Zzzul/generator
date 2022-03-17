<?php

return [
    'route' => 'generators',
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
                    'icon' => '<i class="bi bi-speedometer2"></i>',
                    'route' => '/',
                    'sub_menus' => [],
                ],
                [
                    'title' => 'Generators',
                    'icon' => '<i class="bi bi-grid"></i>',
                    'route' => '/generators/create',
                    'sub_menus' => [],
                ],
                [
                    'title' => 'Main Data',
                    'icon' => '<i class="bi bi-collection"></i>',
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
            'header' => 'Account',
            'menus' => [
                [
                    'title' => 'Users',
                    'icon' => '<i class="bi bi-people"></i>',
                    'route' => '/users',
                    'sub_menus' => [],
                ],
            ],
        ],
    ],
];
