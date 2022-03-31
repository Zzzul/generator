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
        $model = GeneratorUtils::setModelName($request['model']);
        $configSidebar = config('generator.sidebars');

        if ($request['header'] == 'new') {
            $this->generateNewAllMenu(
                request: $request,
                model: $model,
                configSidebar: $configSidebar
            );
        } elseif ($request['menu'] == 'new') {
            $this->generateNewMenu(
                request: $request,
                model: $model,
                configSidebar: $configSidebar
            );
        } else {
            $this->generateNewSubMenu(
                menu: json_decode($request['menu'], true),
                model: $model,
                configSidebar: $configSidebar
            );
        }
    }

    /**
     * Generate a all new sidebar menu(header, menu and submenu) on config.
     *
     * @param array $request
     * @param array $configSidebar
     * @return void
     */
    protected function generateNewAllMenu(array $request, string $model, array $configSidebar)
    {
        $newConfigSidebar = [
            'header' => $request['new_header'],
            'menus' => [],
        ];

        $newRoute = $request['new_route'] ? GeneratorUtils::pluralSnakeCase($request['new_route']) : GeneratorUtils::pluralSnakeCase($model);

        $newMenuTitle = $request['new_menu'] ? GeneratorUtils::pluralKebabCase($request['new_menu']) : GeneratorUtils::pluralKebabCase($model);

        $newMenu = $this->setNewMenu(
            title: $newMenuTitle,
            icon: $request['new_icon'],
            route: '/' . $newRoute,
            submenu: isset($request['new_submenu']) ? $request['new_submenu'] : null
        );

        // push new menu to new config sidebar.menu
        array_push($newConfigSidebar['menus'], $newMenu);

        // push new config sidebar to old config sidebar
        array_push($configSidebar, $newConfigSidebar);

        $stringCode = $this->convertJsonToArrayString($configSidebar);

        $this->generateFile($stringCode);
    }

    /**
     * Generate a new sidebar menu on config.
     *
     * @param array $request
     * @param string $model
     * @param array $configSidebar
     * @return void
     */
    protected function generateNewMenu(array $request, string $model, array $configSidebar)
    {
        $newRoute = $request['new_route'] ? GeneratorUtils::pluralSnakeCase($request['new_route']) : GeneratorUtils::pluralSnakeCase($model);

        $newMenuTitle = $request['new_menu'] ? GeneratorUtils::pluralKebabCase($request['new_menu']) : GeneratorUtils::pluralKebabCase($model);

        array_push($configSidebar[$request['header']]['menus'], $this->setNewMenu(
            title: $newMenuTitle,
            icon: $request['new_icon'],
            route: '/' . $newRoute,
            submenu: isset($request['new_submenu']) ? $request['new_submenu'] : null
        ));

        $stringCode = $this->convertJsonToArrayString($configSidebar);

        $this->generateFile($stringCode);
    }

    /**
     * Generate a new sidebar submenu on config.
     *
     * @param array $menu
     * @param string $model
     * @param array $configSidebar
     * @return void
     */
    protected function generateNewSubMenu(array $menu, string $model, array $configSidebar)
    {
        $indexSidebar = $menu['sidebar'];
        $indexMenu = $menu['menus'];

        array_push($configSidebar[$indexSidebar]['menus'][$indexMenu]['sub_menus'], [
            'title' => GeneratorUtils::cleanPluralUcWords($model),
            'route' => '/' . GeneratorUtils::cleanPluralLowerCase($model)
        ]);

        $stringCode = $this->convertJsonToArrayString($configSidebar);

        $this->generateFile($stringCode);
    }

    /**
     * Replace code on config with newly string code.
     *
     * @param string $jsonToArrayString
     * @return void
     */
    protected function generateFile(string $jsonToArrayString)
    {
        $template = "<?php " . PHP_EOL . "\nreturn [ " . PHP_EOL . "\t'data_types' => " . $this->getDataTypes() . "," . PHP_EOL . "\t'sidebars' => " . $jsonToArrayString . "\n];";

        file_put_contents(base_path('config/generator.php'), $template);
    }

    /**
     * Get all avaible data types on config(array), convert to json and convert again to string(format like an array)
     *
     * @return string
     */
    protected function getDataTypes()
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
    protected function convertJsonToArrayString(array $replace)
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
     * Set new menu and check if request submenu exist or not, if exist push submenu to menu.
     *
     * @param string $title
     * @param string $icon
     * @param string $route
     * @param string|null $submenu
     * @return array $newMenu
     */
    protected function setNewMenu(string $title, string $icon, string $route, string|null $submenu = null)
    {
        if (isset($submenu)) {
            $newMenu = [
                'title' => GeneratorUtils::cleanPluralUcWords($title),
                'icon' => $icon,
                'route' => null,
                'sub_menus' => [
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
