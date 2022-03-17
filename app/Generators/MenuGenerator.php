<?php

namespace App\Generators;

class MenuGenerator
{
    /**
     * Generate a menu from a given array.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $menu =  json_decode($request['menu'], true);
        $sidebars = config('generator.sidebars');
        $route = config('generator.route');

        $this->generateSubMenu(menu: $menu, sidebars: $sidebars, route: $route, model: GeneratorUtils::setModelName($request['model']));

        // if ($menu['sidebar'] != 'new') {
        //     $this->generateSubMenu(menu: $menu, sidebars: $sidebars, route: $route, model: GeneratorUtils::setModelName($request['model']));
        // } else {
        //     $this->generateMenu(request: $request['sidebars'], sidebars: $sidebars, route: $route);
        // }
    }

    /**
     * Generate a new sidebar menu on config.
     *
     * @param array $request
     * @param array $sidebars
     * @param string $route
     * @return void
     */
    private function generateMenu(array $request, array $sidebars, string $route)
    {
        array_push($sidebars, [
            'header' => $request['header'],
            'menus' => [
                [
                    'title' => $request['title'],
                    'icon' => $request['icon'],
                    'route' => $request['route'],
                    'sub_menus' => [
                        [
                            'title' => $request['sub_menu'],
                            'route' => '/' . $request['sub_menu']
                        ]
                    ],
                ],
            ],
        ]);

        $dataTypes = $this->getDataTypes();
        $jsonToArrayText = $this->convertJsonToArrayText($sidebars);

        $this->generateFile(route: $route, dataTypes: $dataTypes, jsonToArrayText: $jsonToArrayText);
    }

    /**
     * Generate a new sidebar submenu on config.
     *
     * @param array $menu
     * @param array $sidebars
     * @param string $route
     * @param string $model
     * @return void
     */
    private function generateSubMenu(array $menu, array $sidebars, string $route, string $model)
    {
        $titleMenu = GeneratorUtils::cleanPluralUcWords($model);
        $routeMenu = GeneratorUtils::cleanPluralLowerCase($model);

        $dataTypes = $this->getDataTypes();

        $search = json_encode($sidebars[$menu['sidebar']]['menus'][$menu['menus']]['route']) . ',"sub_menus":[';

        // convert json to array
        $replace = json_decode(str_replace(
            $search,
            $search . json_encode(['title' => $titleMenu, 'route' => "/$routeMenu"]),
            json_encode($sidebars)
        ), true);

        // sometimes will return null if exists sub_menus, this code for handle it. ad extra ',' at the end
        if ($replace == null) {
            $replace = json_decode(str_replace(
                $search,
                $search . json_encode(['title' => $titleMenu, 'route' => "/$routeMenu"]) . ',',
                json_encode($sidebars)
            ), true);
        }

        $jsonToArrayText = $this->convertJsonToArrayText($replace);

        $this->generateFile(route: $route, dataTypes: $dataTypes, jsonToArrayText: $jsonToArrayText);
    }

    /**
     * Replace code on config.
     *
     * @param string $route
     * @param string $dataTypes
     * @param string $jsonToArrayText
     * @return void
     */
    private function generateFile(string $route, string $dataTypes, string $jsonToArrayText)
    {
        $jsonToArrayText = "<?php " . PHP_EOL . "\nreturn [ " . PHP_EOL . "\t'route' => '$route'," . PHP_EOL . "\t'data_types' => $dataTypes," . PHP_EOL . "\t'sidebars' => " . $jsonToArrayText . "\n];";

        file_put_contents(base_path('config/generator.php'), $jsonToArrayText);
    }

    /**
     * Get data types on config(array), convert to json and convert again to string(format like an array)
     *
     * @return string
     */
    private function getDataTypes()
    {
        return str_replace(
            [
                '",',
                '"',
                "['",
                "']",
                "\t'",
            ],
            [
                "', \n\t",
                "'",
                "[\n\t'",
                "'\n\t]",
                "\t\t'"
            ],
            json_encode(config('generator.data_types'))
        );
    }

    /**
     * Convert json to string(format like an array).
     *
     * @param array $replace
     * @return string
     */
    private function convertJsonToArrayText(array $replace)
    {
        return str_replace(
            [
                '{',
                '}',
                ':',
                '"',
                "','",
                "\\",
                "='",
                "'>"
            ],
            [
                '[',
                ']',
                ' => ',
                "'",
                "', '",
                '',
                '="',
                '">'
            ],
            json_encode($replace, JSON_PRETTY_PRINT)
        );
    }
}
