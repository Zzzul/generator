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

        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($model);
        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($model);

        $trs = "";
        $totalFields = count($request['fields']);
        $dateTimeFormat = config('generator.format.datetime') ? config('generator.format.datetime') : 'd/m/Y H:i';

        foreach ($request['fields'] as $i => $field) {
            if ($request['input_types'][$i] != 'password') {
                if ($i >= 1) {
                    $trs .= "\t\t\t\t\t\t\t\t\t";
                }

                $fieldUcWords = GeneratorUtils::cleanUcWords($field);
                $fieldSnakeCase = str($field)->snake();

                if (isset($request['file_types'][$i]) && $request['file_types'][$i] == 'image') {
                    $default = GeneratorUtils::setDefaultImage(
                        default: $request['default_values'][$i],
                        field: $request['fields'][$i],
                        model: $model
                    );

                    $uploadPath =  config('generator.image.path') == 'storage' ? "storage/uploads/" : "uploads/";

                    $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                        <td>
                                            @if (". $default['form_code'] .")
                                            <img src=\"". $default['image'] ."\" alt=\"$fieldUcWords\"  class=\"rounded\" width=\"200\" height=\"150\" style=\"object-fit: cover\">
                                            @else
                                                <img src=\"{{ asset('$uploadPath" . str($field)->plural()->snake() . "/' . $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . ") }}\" alt=\"$fieldUcWords\" class=\"rounded\" width=\"200\" height=\"150\" style=\"object-fit: cover\">
                                            @endif
                                        </td>
                                    </tr>";
                }

                switch ($request['column_types'][$i]) {
                    case 'boolean':
                        $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " == 1 ? 'True' : 'False' }}</td>
                                    </tr>";
                        break;
                    case 'foreignId':
                        // remove '/' or sub folders
                        $constrainModel = GeneratorUtils::setModelName($request['constrains'][$i]);

                        $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('" . GeneratorUtils::cleanSingularUcWords($constrainModel) . "') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . " ? $" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "->" . GeneratorUtils::getColumnAfterId($constrainModel) . " : '' }}</td>
                                    </tr>";
                        break;
                    case 'date':
                        $dateFormat = config('generator.format.date') ? config('generator.format.date') : 'd/m/Y';

                        if ($request['input_types'][$i] == 'month') {
                            $dateFormat = config('generator.format.month') ? config('generator.format.month') : 'm/Y';
                        }

                        $trs .= "<tr>
                                            <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                            <td>{{ isset($" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . ") ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('$dateFormat') : ''  }}</td>
                                        </tr>";
                        break;
                    case 'dateTime':
                        $trs .= "<tr>
                                            <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                            <td>{{ isset($" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . ") ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('$dateTimeFormat') : ''  }}</td>
                                        </tr>";
                        break;
                    case 'time':
                        $timeFormat = config('generator.format.time') ? config('generator.format.time') : 'H:i';

                        $trs .= "<tr>
                                            <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                            <td>{{ isset($" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . ") ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('$timeFormat') : ''  }}</td>
                                        </tr>";
                        break;
                    default:
                        if ($request['file_types'][$i] != 'image') {
                            $trs .= "<tr>
                                            <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                            <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " }}</td>
                                        </tr>";
                        }
                        break;
                }

                if ($i + 1 != $totalFields) {
                    $trs .= "\n";
                }
            }
        }

        $template = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSingularCamelCase}}',
                '{{trs}}',
                '{{dateTimeFormat}}'
            ],
            [
                GeneratorUtils::cleanPluralUcWords($model),
                GeneratorUtils::cleanSingularLowerCase($model),
                $modelNamePluralKebabCase,
                $modelNameSingularCamelCase,
                $trs,
                $dateTimeFormat
            ],
            GeneratorUtils::getTemplate('views/show')
        );

        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase"));
                file_put_contents(resource_path("/views/$modelNamePluralKebabCase/show.blade.php"), $template);
                break;
            default:
                $fullPath = resource_path("/views/" . strtolower($path) . "/$modelNamePluralKebabCase");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents($fullPath . "/show.blade.php", $template);
                break;
        }
    }
}
