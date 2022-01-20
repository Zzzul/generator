<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;
use Illuminate\Support\Str;

class GenerateIndexView
{
    public function execute($request)
    {
        $modelNamePluralUppercase = Str::plural(ucfirst($request['model']), 2);
        $modelNameSingularUppercase = Str::singular(ucfirst($request['model']));

        $modelNamePluralLowercase = Str::plural(strtolower($request['model']), 2);
        $modelNameSingularLowercase = Str::singular(strtolower($request['model']));

        $thColums = '';
        $tdColumns = '';
        $totalFields = count($request['fields']);

        foreach ($request['fields'] as $i => $field) {
            /**
             * will generate like:
             * <th>{{ __('Price') }}</th>
             */
            $thColums .= "<th>{{ __('" .  ucwords(str_replace('_', ' ', $field)) . "') }}</th>";

            /**
             * will generate like:
             * {
                    data: 'price',
                    name: 'price'
                }
             */

            $tdColumns .= "{
                    data: '" . Str::snake(strtolower($field)) . "',
                    name: '" . Str::snake(strtolower($field)) . "'
                },";

            if ($i + 1 != $totalFields) {
                // add new line and tab
                $thColums .= "\n\t\t\t\t\t\t\t\t\t\t\t";
                $tdColumns .= "\n\t\t\t\t";
            }
        }

        $template = str_replace(
            [
                '{{modelNamePluralUppercase}}',
                '{{modelNamePluralLowercase}}',
                '{{modelNameSingularLowercase}}',
                '{{modelNameSingularUppercase}}',
                '{{thColumns}}',
                '{{tdColumns}}'
            ],
            [
                $modelNamePluralUppercase,
                $modelNamePluralLowercase,
                $modelNameSingularLowercase,
                $modelNameSingularUppercase,
                $thColums,
                $tdColumns
            ],
            GeneratorUtils::getTemplate('views/index')
        );

        GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralLowercase"));

        GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralLowercase/index.blade.php"), $template);
    }
}
