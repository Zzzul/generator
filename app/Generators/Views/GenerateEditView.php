<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

class GenerateEditView
{
    /**
     * Generate a edit view
     * @param array $request
     * @return void
     */
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
                '{{modelNameSingularCamelCase}}',
                '{{enctype}}'
            ],
            [
                $modelNamePluralUcWords,
                $modelNameSingularLowerCase,
                $modelNamePluralKebabCase,
                $modelNameSingularCamelCase,
                in_array('file', $request['input_types']) ? ' enctype="multipart/form-data"' : ''
            ],
            GeneratorUtils::getTemplate('views/edit')
        );

        GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase"));

        GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralKebabCase/edit.blade.php"), $template);
    }
}
