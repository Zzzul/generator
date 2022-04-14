<?php

return [
    /**
     * Its used for route and sidebar menu name.
     */
    'name' => 'generators',

    /**
     * All avaibale column type for migration.
     */
    'data_type' => [
        'string',
        'integer',
        'text',
        'bigInteger',
        'boolean',
        'char',
        'date',
        'time',
        'year',
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

    /**
     * If any input file(image) as default will used options below.
     */
    'image' => [
        /**
         * Path for image store into.
         *
         * avaiable options:
         * 1. public
         * 2. storage
         */
        'path' => 'public',

        /**
         * Will used if image is nullable.
         */
        'default' => 'https://via.placeholder.com/350?text=No+Image+Available',

        /**
         * Crop the uploaded image used intervention image.
         *
         * when set to false will ignore config below(aspect_ratio, width, and height).
         */
        'crop' => true,

        /**
         * When set to true the uploaded image aspect ratio will still original.
         */
        'aspect_ratio' => true,

        /**
         * Crop image size.
         */
        'width' => 500,
        'height' => 500,
    ],

    'format' => [
        /**
         * Will used to first year on select, if any column type year.
         */
        'first_year' => 1900,

        /**
         * If any date column type will cast and display used this format, but for input date still will used Y-m-d format.
         *
         * another most common format:
         * - M d Y
         * - d F Y
         * - Y m d
         */
        'date' => 'd/m/Y',

        /**
         * If any time column type will cast and display used this format.
         */
        'time' => 'H:i',

        /**
         * If any datetime column type or datetime-local on input format will cast and display used this format.
         */
        'datetime' => 'd/m/Y H:i'
    ],

    /**
     * It will used for generator to manage and show menus on sidebar views.
     */
    'sidebars' => [
        [
            'header' => 'Main',
            /**
             * All permissions in menus[] and sub_menus[]
             */
            'permissions' => ['view test'],
            'menus' => [
                [
                    'title' => 'Main Data',
                    'icon' => '<i class="bi bi-collection-fill"></i>',
                    'route' => null,
                    'permission' => null,
                    /**
                     * All permissions on sub_menus[]
                     */
                    'permissions' => ['view test'],
                    'sub_menus' => [
                        [
                            'title' => 'Tests',
                            'route' => '/tests',
                            'permission' => 'view test'
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
                    'permissions' => [],
                    'sub_menus' => [],
                ],
                [
                    'title' => 'Roles & permissions',
                    'icon' => '<i class="bi bi-person-check-fill"></i>',
                    'route' => '/roles',
                    'permission' => 'view role & permission',
                    'permissions' => [],
                    'sub_menus' => [],
                ]
            ],
        ],
    ],
];
