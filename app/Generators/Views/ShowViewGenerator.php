<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

class ShowViewGenerator
{
    /**
     * Generate a show view.
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
        $modelNameSingularLowerCase = GeneratorUtils::cleanSingularLowerCase($model);

        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($model);

        $trs = "";
        $totalFields = count($request['fields']);

        foreach ($request['fields'] as $i => $field) {
            if ($i >= 1) {
                $trs .= "\t\t\t\t\t\t\t\t\t";
            }

            $fieldUcWords = GeneratorUtils::cleanUcWords($field);
            $fieldSnakeCase = str($field)->snake();

            if ($request['file_types'][$i] == 'image') {
                $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                        <td>
                                            @if ($" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " == null)
                                            <img src=\"https://via.placeholder.com/350?text=No+Image+Available\" alt=\"$fieldUcWords\"  class=\"rounded\" width=\"200\" style=\"object-fit: cover\">
                                            @else
                                                <img src=\"{{ asset('uploads/" . str($field)->plural()->snake() . "/' . $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . ") }}\" alt=\"$fieldUcWords\" class=\"rounded\" width=\"200\" style=\"object-fit: cover\">
                                            @endif
                                        </td>
                                    </tr>";
            } elseif ($request['data_types'][$i] == 'date') {
                $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('d/m/Y') }}</td>
                                    </tr>";
            } elseif ($request['data_types'][$i] == 'dateTime') {
                $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('d/m/Y H:i') }}</td>
                                    </tr>";
            } elseif ($request['data_types'][$i] == 'boolean') {
                $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " == 1 ? 'True' : 'False' }}</td>
                                    </tr>";
            } elseif ($request['data_types'][$i] == 'foreignId') {
                // remove '/' or sub folders
                $constrainModel = GeneratorUtils::setModelName($request['constrains'][$i]);

                $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('" . GeneratorUtils::cleanSingularUcWords($constrainModel) . "') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . " ? $" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "->" . GeneratorUtils::getColumnAfterId($constrainModel) . " : '' }}</td>
                                    </tr>";
            } else {
                $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " }}</td>
                                    </tr>";
            }

            if ($i + 1 != $totalFields) {
                $trs .= "\n";
            }
        }

        $template = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSingularCamelCase}}',
                '{{trs}}'
            ],
            [
                $modelNamePluralUcWords,
                $modelNameSingularLowerCase,
                $modelNamePluralKebabCase,
                $modelNameSingularCamelCase,
                $trs
            ],
            GeneratorUtils::getTemplate('views/show')
        );

        if ($path != '') {
            $fullPath = resource_path("/views/" . strtolower($path) . "/$modelNamePluralKebabCase");

            GeneratorUtils::checkFolder($fullPath);

            file_put_contents($fullPath . "/show.blade.php", $template);
        } else {
            GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase"));

            file_put_contents(resource_path("/views/$modelNamePluralKebabCase/show.blade.php"), $template);
        }
    }
}
