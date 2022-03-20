<?php

namespace App\Generators;

class MenuGenerator
{
    private $confidgSidebars, $configRoute;

    public function __construct()
    {
        $this->confidgSidebars = config('generators.sidebars');
        $this->configRoute = config('generators.routes');
    }

    /**
     * Generate a menu from a given array.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $model = GeneratorUtils::setModelName($request['model']);

        if ($request['header'] == 'new') {
            $this->generateNewAllMenu(request: $request, model: $model);
        } elseif ($request['menu'] == 'new') {
            $this->generateNewMenu(request: $request, model: $model);
        } else {
            $this->generateNewSubMenu(menu: json_decode($request['menu'], true), model: $model);
        }
    }

    /**
     * Generate a all new sidebar menu(header, menu and submenu) on config.
     *
     * @param array $request
     * @return void
     */
    private function generateNewAllMenu(array $request, string $model)
    {
        $newConfigSidebar = [
            'header' => $request['new_header'],
            'menus' => [],
        ];

        $newRoute = $request['new_route'] ? GeneratorUtils::pluralSnakeCase($request['new_route']) : GeneratorUtils::pluralSnakeCase($model);

        $newMenu = $this->setNewMenu(
            title: $request['new_menu'],
            icon: $request['new_icon'],
            route: '/' . $newRoute,
            submenu: isset($request['new_submenu']) ? $request['new_submenu'] : null
        );

        // push new menu to new config sidebar.menu
        array_push($newConfigSidebar['menus'], $newMenu);

        // push new config sidebar to old config sidebar
        array_push($this->confidgSidebars, $newConfigSidebar);

        $this->generateFile(
            dataTypes: $this->getDataTypes(),
            jsonToArrayString: $this->convertJsonToArrayString($this->confidgSidebars)
        );
    }

    private function generateNewMenu(array $request, string $model)
    {
        $totalMenus = count($this->confidgSidebars[$request['header']]['menus']) - 1;
        $newRoute = $request['new_route'] ? GeneratorUtils::pluralSnakeCase($request['new_route']) : GeneratorUtils::pluralSnakeCase($model);

        // get latest menus, convert to json(stirng) and remove latest char on the string
        $search = substr(json_encode($this->confidgSidebars[$request['header']]['menus'][$totalMenus]), 0, -1);

        $newMenu = $this->setNewMenu(
            title: $request['new_menu'],
            icon: $request['new_icon'],
            route: '/' . $newRoute,
            submenu: isset($request['new_submenu']) ? $request['new_submenu'] : null
        );

        // convert json to array
        $replace = str_replace(
            $search,
            // add }, to make valid json
            $search . '},' . json_encode($newMenu),
            json_encode($this->confidgSidebars)
        );

        // remove ]}}]} caouse will make invalid json format and can't convert to array
        $replace2 = json_decode(str_replace(']}}]}', ']}]}', $replace), true);

        $this->generateFile(
            dataTypes: $this->getDataTypes(),
            jsonToArrayString: $this->convertJsonToArrayString($replace2)
        );
    }

    /**
     * Generate a new sidebar submenu on config.
     *
     * @param array $menu
     * @param string $model
     * @return void
     */
    private function generateNewSubMenu(array $menu, string $model)
    {
        $titleMenu = GeneratorUtils::cleanPluralUcWords($model);
        $this->configRouteMenu = GeneratorUtils::cleanPluralLowerCase($model);

        $search = json_encode($this->confidgSidebars[$menu['sidebar']]['menus'][$menu['menus']]['route']) . ',"sub_menus":[';

        // convert json to array
        $replace = json_decode(str_replace(
            $search,
            $search . json_encode(['title' => $titleMenu, 'route' => "/$this->configRouteMenu"]),
            json_encode($this->confidgSidebars)
        ), true);

        // sometimes will return null if exists sub_menus, this code for handle it. ad extra ',' at the end
        if ($replace == null) {
            $replace = json_decode(str_replace(
                $search,
                $search . json_encode(['title' => $titleMenu, 'route' => "/$this->configRouteMenu"]) . ',',
                json_encode($this->confidgSidebars)
            ), true);
        }

        $this->generateFile(
            dataTypes: $this->getDataTypes(),
            jsonToArrayString: $this->convertJsonToArrayString($replace)
        );
    }

    /**
     * Replace code on config with newly code(string).
     *
     * @param string $dataTypes
     * @param string $jsonToArrayString
     * @return void
     */
    private function generateFile(string $dataTypes, string $jsonToArrayString)
    {
        $template = "<?php " . PHP_EOL . "\nreturn [ " . PHP_EOL . "\t'route' => '" . $this->configRoute . "'," . PHP_EOL . "\t'data_types' => $dataTypes," . PHP_EOL . "\t'sidebars' => " . $jsonToArrayString . "\n];";

        file_put_contents(base_path('config/generator.php'), $template);
    }

    /**
     * Get all avaible data types on config(array), convert to json and convert again to string(format like an array)
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
     * Convert json to string with format like an array.
     *
     * @param array $replace
     * @return string
     */
    private function convertJsonToArrayString(array $replace)
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
    /**
     * Set new menu and check if request submenu exist or not, if exist push to menu.
     *
     * @param string $title
     * @param string $icon
     * @param string $route
     * @param string|null $submenu
     * @return array $newMenu
     */
    private function setNewMenu(string $title, string $icon, string $route, string|null $submenu = null)
    {
        if (isset($submenu)) {
            $newMenu = [
                'title' => GeneratorUtils::cleanPluralUcWords($title),
                'icon' => $icon,
                'route' => null,
                'submenus' => [
                    [
                        'title' => $submenu,
                        'route' => '/' . GeneratorUtils::pluralKebabCase($submenu)
                    ]
                ]
            ];
        } else {
            $newMenu = [
                'title' => GeneratorUtils::cleanPluralUcWords($title),
                'icon' => $icon,
                'route' => $route,
                'sub_menus' =>  []
            ];
        }

        return $newMenu;
    }
}