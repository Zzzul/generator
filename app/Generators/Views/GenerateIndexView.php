<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

class GenerateIndexView
{
    /**
     * Generate a index view
     * @param array $request
     * @return void
     */
    public function execute(array $request)
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
            if ($request['data_types'][$i] != 'foreignId') {
                $thColums .= "<th>{{ __('" .  GeneratorUtils::cleanSingularUcWords($field) . "') }}</th>";
            }

            if ($request['input_types'][$i] == 'file') {
                /**
                 * will generate like:
                 * {
                 *    data: 'photo',
                 *    name: 'photo',
                 *    orderable: false,
                 *    searchable: false,
                 *    render: function(data, type, full, meta) {
                 *        return `<div class="avatar">
                 *            <img src="${data}" alt="Photo">
                 *        </div>`;
                 *    }
                 * },
                 */

                $tdColumns .=  "{
                    data: '" . GeneratorUtils::singularSnakeCase($field) . "',
                    name: '" . GeneratorUtils::singularSnakeCase($field) . "',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        return `<div class=\"avatar\">
                            <img src=\"" . '$' . "{data}\" alt=\"" . GeneratorUtils::cleanSingularUcWords($field) . "\">
                        </div>`;
                        }
                    },";
            } elseif ($request['data_types'][$i] == 'foreignId') {
                $thColums .= "<th>{{ __('" .  GeneratorUtils::cleanSingularUcWords($request['constrains'][$i]) . "') }}</th>";

                /**
                 * will generate like:
                 * {
                 *    data: 'user',
                 *    name: 'user.name'
                 * }
                 */
                $tdColumns .=  "{
                    data: '" . GeneratorUtils::singularSnakeCase($request['constrains'][$i]) . "',
                    name: '" . GeneratorUtils::singularSnakeCase($request['constrains'][$i]) . "." . GeneratorUtils::getColumnAfterId($request['constrains'][$i]) . "'
                },";
            } else {
                /**
                 * will generate like:
                 * {
                 *    data: 'price',
                 *    name: 'price'
                 * }
                 */
                $tdColumns .= "{
                    data: '" . GeneratorUtils::singularSnakeCase($field) . "',
                    name: '" . GeneratorUtils::singularSnakeCase($field) . "'
                },";
            }

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
