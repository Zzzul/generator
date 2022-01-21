<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

class GenerateIndexView
{
    public function execute($request)
    {
        $modelNamePluralUcWords = GeneratorUtils::cleanPluralUcWords($request['model']);

        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($request['model']);

        $modelNamePluralLowerCase = GeneratorUtils::cleanPluralLowerCase($request['model']);

        $modelNameSingularLowercase = GeneratorUtils::cleanSingularLowerCase($request['model']);

        $thColums = '';
        $tdColumns = '';
        $totalFields = count($request['fields']);

        foreach ($request['fields'] as $i => $field) {
            /**
             * will generate like:
             * <th>{{ __('Price') }}</th>
             */
            $thColums .= "<th>{{ __('" .  GeneratorUtils::cleanSingularUcWords($field) . "') }}</th>";

            /**
             * will generate like:
             * {
                    data: 'price',
                    name: 'price'
                }
             */

            $tdColumns .= "{
                    data: '" . GeneratorUtils::singularSnakeCase($field) . "',
                    name: '" . GeneratorUtils::singularSnakeCase($field) . "'
                },";

            if ($i + 1 != $totalFields) {
                // add new line and tab
                $thColums .= "\n\t\t\t\t\t\t\t\t\t\t\t";
                $tdColumns .= "\n\t\t\t\t";
            }
        }

        $template = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralLowerCase}}',
                '{{thColumns}}',
                '{{tdColumns}}'
            ],
            [
                $modelNamePluralUcWords,
                $modelNamePluralKebabCase,
                $modelNameSingularLowercase,
                $modelNamePluralLowerCase,
                $thColums,
                $tdColumns
            ],
            GeneratorUtils::getTemplate('views/index')
        );

        GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase"));

        GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralKebabCase/index.blade.php"), $template);
    }
}
