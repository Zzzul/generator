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
            'permissions' => [null, 'view product', 'view book'],
            'menus' => [
                [
                    'title' => 'Dashboard',
                    'icon' => '<i class="bi bi-speedometer"></i>',
                    'route' => '/',
                    'permission' => null,
                    'permissions' => null,
                    'sub_menus' => [],
                ],
                [
                    'title' => 'Main Data',
                    'icon' => '<i class="bi bi-collection-fill"></i>',
                    'route' => null,
                    'permission' => null,
                    'permissions' => ['view product', 'view book'],
                    'sub_menus' => [
                        [
                            'title' => 'Products',
                            'route' => '#',
                            'permission' => 'view product'
                        ],
                        [
                            'title' => 'Books',
                            'route' => '#',
                            'permission' => 'view book'
                        ]
                    ],
                ],
            ],
        ],
        [
            'header' => 'Users',
            'permissions' => ['view user', 'view role & permission'],
            'menus' => [
                [
                    'title' => 'Users',
                    'icon' => '<i class="bi bi-people-fill"></i>',
                    'route' => '/users',
                    'permission' => 'view user',
                    'permissions' => null,
                    'sub_menus' => [],
                ],
                [
                    'title' => 'Roles & permissions',
                    'icon' => '<i class="bi bi-person-check-fill"></i>',
                    'route' => '/roles',
                    'permission' => 'view role & permission',
                    'permissions' => null,
                    'sub_menus' => [],
                ]
            ],
        ],
        [
            'header' => 'Generators',
            'permissions' => null,
            'menus' => [
                [
                    'title' => 'CRUD Generator',
                    'icon' => '<i class="bi bi-grid"></i>',
                    'route' => '/generators/create',
                    'permission' => null,
                    'permissions' => null,
                    'sub_menus' => [],
                ],
            ],
        ],
        [
            'header' => 'Test',
            'permissions' => ['view test 1', 'view test 2', 'view test 3'],
            'menus' => [
                [
                    'title' => 'Test 1',
                    'icon' => '<i class="bi bi-speedometer"></i>',
                    'route' => '/',
                    'permission' => 'view test 1',
                    'permissions' => null,
                    'sub_menus' => [],
                ],
                [
                    'title' => 'Test 2 & 3',
                    'icon' => '<i class="bi bi-collection-fill"></i>',
                    'route' => null,
                    'permission' => null,
                    'permissions' => ['view test 2', 'view test 3'],
                    'sub_menus' => [
                        [
                            'title' => 'Test 2',
                            'route' => '#',
                            'permission' => 'view test 2'
                        ],
                        [
                            'title' => 'Test 3',
                            'route' => '#',
                            'permission' => 'view test 3'
                        ]
                    ],
                ],
            ],
        ],
    ],
];
