<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

class IndexViewGenerator
{
    /**
     * Generate an index view.
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
        $modelNamePluralLowerCase = GeneratorUtils::cleanPluralLowerCase($model);
        $modelNameSingularLowercase = GeneratorUtils::cleanSingularLowerCase($model);

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

        if ($path != '') {
            $fullPath = resource_path("/views/$path/$modelNamePluralKebabCase");

            GeneratorUtils::checkFolder($fullPath);

            GeneratorUtils::generateTemplate($fullPath . "/index.blade.php", $template);
        } else {
            GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase"));

            GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralKebabCase/index.blade.php"), $template);
        }
    }
}
