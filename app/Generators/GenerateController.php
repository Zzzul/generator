<?php

namespace App\Generators;

class GenerateController
{
    public function execute($request)
    {
        $modelNameUppercase = GeneratorUtils::singularPascalCase($request['model']);
        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($request['model']);
        $modelNamePluralCamelCase = GeneratorUtils::pluralCamelCase($request['model']);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($request['model']);
        $modelNameSpaceLowercase = GeneratorUtils::cleanSingularLowerCase($request['model']);

        $template = str_replace(
            [
                '{{modelNameUppercase}}',
                '{{modelNameSingularCamelCase}}',
                '{{modelNamePluralCamleCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSpaceLowercase}}'
            ],
            [
                $modelNameUppercase,
                $modelNameSingularCamelCase,
                $modelNamePluralCamelCase,
                $modelNamePluralKebabCase,
                $modelNameSpaceLowercase
            ],
            GeneratorUtils::getTemplate('controller')
        );

        GeneratorUtils::generateTemplate(app_path("/Http/Controllers/{$modelNameUppercase}Controller.php"), $template);
    }
}
