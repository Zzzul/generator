<?php

return [
    'route' => 'generators',
    'data_types' => [
        'string',
        'integer',
        'text',
        'bigInteger',
        'boolean',
        'varchar',
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
                    'uri' => '/',
                ],
                [
                    'title' => 'Generators',
                    'icon' => '<i class="bi bi-grid"></i>',
                    'uri' => '/generators/create',
                ],
                [
                    'title' => 'Master Data',
                    'icon' => '<i class="bi bi-collection"></i>',
                    'uri' => null,
                    'sub_menus' => [
                        [
                            'title' => 'Products',
                            'uri' => '/products'
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
                    'uri' => '/users'
                ],
            ],
        ],
    ],
];
