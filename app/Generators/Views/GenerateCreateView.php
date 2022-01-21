<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

class GenerateCreateView
{
    public function execute(array $request)
    {
        $modelNamePluralUcWords = GeneratorUtils::cleanPluralUcWords($request['model']);

        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($request['model']);
        $modelNameSingularLowerCase = GeneratorUtils::cleanSingularLowerCase($request['model']);

        $template = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}'
            ],
            [
                $modelNamePluralUcWords,
                $modelNameSingularLowerCase,
                $modelNamePluralKebabCase
            ],
            GeneratorUtils::getTemplate('views/create')
        );

        GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase"));

        GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralKebabCase/create.blade.php"), $template);
    }
}
