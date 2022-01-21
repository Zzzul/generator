<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;
use Illuminate\Support\Str;

class GenerateFormView
{
    public function execute(array $request)
    {
        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($request['model']);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($request['model']);

        $template = "<div class=\"row mb-2\">\n";

        foreach ($request['fields'] as $i => $field) {

            $fieldSnakeCase = GeneratorUtils::singularSnakeCase($field);
            $fieldUcWords = GeneratorUtils::cleanSingularUcWords($field);

            if ($request['types'][$i] == 'enum') {
                $lists = "";

                $options = explode(';', $request['select_options'][$i]);

                $totalOptions = count($options);

                foreach ($options as $key => $value) {
                    $lists .= "<option value=\"$value\" {{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$field == '$value' ? 'selected' : (old('$field') == '$value' ? 'selected' : '') }}>$value</option>";

                    if ($key + 1 != $totalOptions) {
                        $lists .= "\n\t\t\t\t";
                    } else {
                        $lists .= "\t\t\t\t";
                    }
                }

                // select
                $template .= str_replace(
                    [
                        '{{fieldLowercase}}',
                        '{{fieldUppercase}}',
                        '{{options}}',
                        '{{nullable}}'
                    ],
                    [
                        $fieldSnakeCase,
                        $fieldUcWords,
                        $lists,
                        isset($request['requireds'][$i]) ? ' required' : '',
                    ],
                    GeneratorUtils::getTemplate('views/forms/select')
                );
            } else if (Str::contains($request['types'][$i], 'text')) {

                // textarea
                $template .= str_replace(
                    [
                        '{{fieldLowercase}}',
                        '{{fieldUppercase}}',
                        '{{modelName}}',
                        '{{nullable}}'
                    ],
                    [
                        $fieldSnakeCase,
                        $fieldUcWords,
                        $modelNameSingularCamelCase,
                        isset($request['requireds'][$i]) ? ' required' : '',
                    ],
                    GeneratorUtils::getTemplate('views/forms/textarea')
                );
            } else {

                // input
                $fieldSnakeCase = $fieldSnakeCase;

                $formatValue = "{{ isset($$modelNameSingularCamelCase) ? $$modelNameSingularCamelCase->$fieldSnakeCase : old('$fieldSnakeCase') }}";

                if ($request['types'][$i] == 'dateTime') {
                    $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? date('Y-m-d\TH:i', strtotime($" . $modelNameSingularCamelCase . "->$fieldSnakeCase)) : old('$fieldSnakeCase') }}";
                } elseif ($request['types'][$i] == 'date') {
                    $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? date(\"d-m-Y\", strtotime($" . $modelNameSingularCamelCase . "->$fieldSnakeCase)) : old('$fieldSnakeCase') }}";
                }

                $template .= str_replace(
                    [
                        '{{fieldKebabCase}}',
                        '{{fieldUcWords}}',
                        '{{fieldSnakeCase}}',
                        '{{fieldCamelCase}}',
                        '{{modelName}}',
                        '{{type}}',
                        '{{value}}',
                        '{{nullable}}'
                    ],
                    [
                        GeneratorUtils::singularKebabCase($field),
                        $fieldUcWords,
                        $fieldSnakeCase,
                        GeneratorUtils::singularCamelCase($field),
                        $modelNameSingularCamelCase,
                        GeneratorUtils::setInputType($request['types'][$i], $request['fields'][$i]),
                        $formatValue,
                        isset($request['requireds'][$i]) ? ' required' : '',
                    ],
                    GeneratorUtils::getTemplate('views/forms/input')
                );
            }
        }

        $template .= "</div>";

        GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase/include"));

        GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralKebabCase/include/form.blade.php"), $template);
    }
}
