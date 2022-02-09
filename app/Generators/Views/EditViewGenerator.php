<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

class EditViewGenerator
{
    /**
     * Generate an edit view.
     *
     * @param array $request
     * @return void
     */
    public function execute(array $request)
    {
        $model = GeneratorUtils::setModelName($request['model']);
        $path = GeneratorUtils::getModelLocation($request['model']);

        $modelNamePluralUcWords = GeneratorUtils::cleanPluralUcWords($model);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($model);
        $modelNameSingularLowerCase = GeneratorUtils::cleanSingularLowerCase($model);
        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($model);

        $template = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSingularCamelCase}}',
                '{{enctype}}',
                '{{viewPath}}',
            ],
            [
                $modelNamePluralUcWords,
                $modelNameSingularLowerCase,
                $modelNamePluralKebabCase,
                $modelNameSingularCamelCase,
                in_array('file', $request['input_types']) ? ' enctype="multipart/form-data"' : '',
                $path != '' ? GeneratorUtils::pluralKebabCase($path) . "." : ''
            ],
            GeneratorUtils::getTemplate('views/edit')
        );

        if ($path != '') {
            $fullPath = resource_path("/views/" . GeneratorUtils::pluralKebabCase($path) . "/$modelNamePluralKebabCase");

            GeneratorUtils::checkFolder($fullPath);

            GeneratorUtils::generateTemplate($fullPath . "/edit.blade.php", $template);
        } else {
            GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase"));

            GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralKebabCase/edit.blade.php"), $template);
        }
    }
}
