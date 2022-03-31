<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

class ActionViewGenerator
{
    /**
     * Generate a action(table) view.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $model = GeneratorUtils::setModelName($request['model']);
        $path = GeneratorUtils::getModelLocation($request['model']);

        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($model);
        $modelNameSingularLowercase = GeneratorUtils::cleanSingularLowerCase($model);

        $template = str_replace(
            [
                '{{modelNameSingularLowercase}}',
                '{{modelNamePluralKebabCase}}'
            ],
            [
                $modelNameSingularLowercase,
                $modelNamePluralKebabCase
            ],
            GeneratorUtils::getTemplate('views/action')
        );

        if ($path != '') {
            $fullPath = resource_path("/views/" . strtolower($path) . "/$modelNamePluralKebabCase/include");

            GeneratorUtils::checkFolder($fullPath);

            file_put_contents($fullPath . "/action.blade.php", $template);
        } else {
            GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase/include"));

            file_put_contents(resource_path("/views/$modelNamePluralKebabCase/include/action.blade.php"), $template);
        };
    }
}
