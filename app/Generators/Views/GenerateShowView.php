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

            if ($request['file_types'][$i] == 'image') {
                $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('" . GeneratorUtils::cleanSingularUcWords($field) . "') }}</td>
                                        <td>
                                            @if ($" . GeneratorUtils::singularCamelCase($request['model']) . "->" . GeneratorUtils::singularSnakeCase($field) . " == null)
                                            <img src=\"https://via.placeholder.com/150\" alt=\"" . GeneratorUtils::cleanSingularUcWords($field) . "\"  class=\"rounded\" width=\"200\" style=\"object-fit: cover\">
                                            @else
                                                <img src=\"{{ asset('uploads/" . GeneratorUtils::pluralSnakeCase($field) . "/' . $" . GeneratorUtils::singularCamelCase($request['model']) . "->" . GeneratorUtils::singularSnakeCase($field) . ") }}\" alt=\"" . GeneratorUtils::cleanSingularUcWords($field) . "\" class=\"rounded\" width=\"200\" style=\"object-fit: cover\">
                                            @endif
                                        </td>
                                    </tr>";
            } else {
                $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('" . GeneratorUtils::cleanSingularUcWords($field) . "') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($field) . " }}</td>
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
