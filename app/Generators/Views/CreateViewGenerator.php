<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

class CreateViewGenerator
{
    /**
     * Generate a create view.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $model = GeneratorUtils::setModelName($request['model']);
        $path = GeneratorUtils::getModelLocation($request['model']);

        $modelNamePluralUcWords = GeneratorUtils::cleanPluralUcWords($model);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($model);
        $modelNameSingularLowerCase = GeneratorUtils::cleanSingularLowerCase($model);

        $template = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{enctype}}',
                '{{viewPath}}',
            ],
            [
                $modelNamePluralUcWords,
                $modelNameSingularLowerCase,
                $modelNamePluralKebabCase,
                in_array('file', $request['input_types']) ? ' enctype="multipart/form-data"' : '',
                $path != '' ? str_replace('\\', '.', $path) . "." : ''
            ],
            GeneratorUtils::getTemplate('views/create')
        );

        if ($path != '') {
            $fullPath = resource_path("/views/" . strtolower($path) . "/$modelNamePluralKebabCase");

            GeneratorUtils::checkFolder($fullPath);

            file_put_contents($fullPath . "/create.blade.php", $template);
        } else {
            GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase"));

            file_put_contents(resource_path("/views/$modelNamePluralKebabCase/create.blade.php"), $template);
        }
    }
}
