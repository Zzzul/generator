<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

class GenerateShowView
{
    /**
     * Generate a show view
     * @param array $request
     * @return void
     */
    public function execute(array $request)
    {
        $modelNamePluralUcWords = GeneratorUtils::cleanPluralUcWords($request['model']);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($request['model']);
        $modelNameSingularLowerCase = GeneratorUtils::cleanSingularLowerCase($request['model']);

        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($request['model']);

        $trs = "";
        $totalFields = count($request['fields']);

        foreach ($request['fields'] as $i => $field) {
            if ($i >= 1) {
                $trs .= "\t\t\t\t\t\t\t\t\t";
            }

            $fieldSingularUcWords = GeneratorUtils::cleanSingularUcWords($field);
            $fieldSingularSnakeCase = GeneratorUtils::singularSnakeCase($field);

            if ($request['file_types'][$i] == 'image') {
                $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldSingularUcWords') }}</td>
                                        <td>
                                            @if ($" . $modelNameSingularCamelCase . "->" . $fieldSingularSnakeCase . " == null)
                                            <img src=\"https://via.placeholder.com/150\" alt=\"$fieldSingularUcWords\"  class=\"rounded\" width=\"200\" style=\"object-fit: cover\">
                                            @else
                                                <img src=\"{{ asset('uploads/" . GeneratorUtils::pluralSnakeCase($field) . "/' . $" . $modelNameSingularCamelCase . "->" . $fieldSingularSnakeCase . ") }}\" alt=\"$fieldSingularUcWords\" class=\"rounded\" width=\"200\" style=\"object-fit: cover\">
                                            @endif
                                        </td>
                                    </tr>";
            } elseif ($request['data_types'][$i] == 'date') {
                $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldSingularUcWords') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSingularSnakeCase . "->format('d/m/Y') }}</td>
                                    </tr>";
            } elseif ($request['data_types'][$i] == 'dateTime') {
                $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldSingularUcWords') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSingularSnakeCase . "->format('d/m/Y H:i') }}</td>
                                    </tr>";
            } elseif ($request['data_types'][$i] == 'boolean') {
                $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldSingularUcWords') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSingularSnakeCase . " == 1 ? 'True' : 'False' }}</td>
                                    </tr>";
            } else {
                $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldSingularUcWords') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSingularSnakeCase . " }}</td>
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

        GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase"));

        GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralKebabCase/show.blade.php"), $template);
    }
}
