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
    public function generate(array $request)
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
             * will generate something like:
             * <th>{{ __('Price') }}</th>
             */
            if ($request['column_types'][$i] != 'foreignId') {
                $thColums .= "<th>{{ __('" .  GeneratorUtils::cleanUcWords($field) . "') }}</th>";
            }

            if ($request['input_types'][$i] == 'file') {
                /**
                 * will generate something like:
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
                    data: '" . str()->snake($field) . "',
                    name: '" . str()->snake($field) . "',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        return `<div class=\"avatar\">
                            <img src=\"" . '$' . "{data}\" alt=\"" . GeneratorUtils::cleanSingularUcWords($field) . "\">
                        </div>`;
                        }
                    },";
            } elseif ($request['column_types'][$i] == 'foreignId') {
                // remove '/' or sub folders
                $constrainModel = GeneratorUtils::setModelName($request['constrains'][$i]);

                $thColums .= "<th>{{ __('" .  GeneratorUtils::cleanSingularUcWords($constrainModel) . "') }}</th>";

                /**
                 * will generate something like:
                 * {
                 *    data: 'user',
                 *    name: 'user.name'
                 * }
                 */
                $tdColumns .=  "{
                    data: '" . GeneratorUtils::singularSnakeCase($constrainModel) . "',
                    name: '" . GeneratorUtils::singularSnakeCase($constrainModel) . "." . GeneratorUtils::getColumnAfterId($constrainModel) . "'
                },";
            } else {
                /**
                 * will generate something like:
                 * {
                 *    data: 'price',
                 *    name: 'price'
                 * }
                 */
                // $tdColumns .= "{
                //     data: '" . str()->snake($field) . "',
                //     name: '" . str()->snake($field) . "'
                // },";

                $tdColumns .=  "{
                    data: '" . str()->snake($field) . "',
                    name: '" . str()->snake($field) . "',
                    render: function(data, type, full, meta) {
                        return data ? data : '-';
                    }
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
            $fullPath = resource_path("/views/" . strtolower($path) . "/$modelNamePluralKebabCase");

            GeneratorUtils::checkFolder($fullPath);

            file_put_contents($fullPath . "/index.blade.php", $template);
        } else {
            GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase"));

            file_put_contents(resource_path("/views/$modelNamePluralKebabCase/index.blade.php"), $template);
        }
    }
}
