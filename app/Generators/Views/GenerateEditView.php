<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

class GenerateEditView
{
    public function execute(array $request)
    {
        $modelNamePluralUcWords = GeneratorUtils::cleanPluralUcWords($request['model']);

        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($request['model']);
        $modelNameSingularLowerCase = GeneratorUtils::cleanSingularLowerCase($request['model']);

        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($request['model']);

        $template = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSingularCamelCase}}'
            ],
            [
                $modelNamePluralUcWords,
                $modelNameSingularLowerCase,
                $modelNamePluralKebabCase,
                $modelNameSingularCamelCase
            ],
            GeneratorUtils::getTemplate('views/edit')
        );

        GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase"));

        GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralKebabCase/edit.blade.php"), $template);
    }
}
