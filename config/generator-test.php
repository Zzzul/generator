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
        'permissions' => [
            'view test'
        ],
        'menus' => [
            [
                'title' => 'Main Data',
                'icon' => '<i class="bi bi-collection-fill"></i>',
                'route' => null,
                'permission' => null,
                'permissions' => [
                    'view test'
                ],
                'sub_menus' => [
                    [
                        'title' => 'Tests',
                        'route' => '/tests',
                        'permission' => 'view test'
                    ]
                ]
            ]
        ]
    ],
    [
        'header' => 'Users',
        'permissions' => [
            'view user',
            'view role & permission',
            'view employee'
        ],
        'menus' => [
            [
                'title' => 'Users',
                'icon' => '<i class="bi bi-people-fill"></i>',
                'route' => null,
                'permission' => null,
                'permissions' => [
                    'view employee'
                ],
                'sub_menus' => [
                    [
                        'title' => 'Employees',
                        'route' => '/employees'
                    ]
                ]
            ],
            [
                'title' => 'Roles & permissions',
                'icon' => '<i class="bi bi-person-check-fill"></i>',
                'route' => '/roles',
                'permission' => 'view role & permission',
                'permissions' => [],
                'sub_menus' => []
            ]
        ]
    ]
]
];