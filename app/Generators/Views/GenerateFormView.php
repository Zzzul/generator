<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

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

            if ($request['data_types'][$i] == 'enum') {
                $lists = "";

                $options = explode('|', $request['select_options'][$i]);

                $totalOptions = count($options);

                if ($request['input_types'][$i] == 'select') {
                    foreach ($options as $key => $value) {
                        $lists .= "<option value=\"" . GeneratorUtils::cleanSingularLowerCase($value) . "\" {{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '" . GeneratorUtils::cleanSingularLowerCase($field) . "' ? 'selected' : (old('$fieldSnakeCase') == '" . GeneratorUtils::cleanSingularLowerCase($field) . "' ? 'selected' : '') }}>" . GeneratorUtils::cleanSingularUcWords($value) . "</option>";

                        if ($key + 1 != $totalOptions) {
                            $lists .= "\n\t\t";
                        } else {
                            $lists .= "\t\t\t";
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
                } else {
                    $lists .= "\t<div class=\"col-md-6\">\n";

                    foreach ($options as $key => $value) {
                        // $lists .= "
                        // <div class=\"form-check\">
                        //     <input class=\"form-check-input\" type=\"radio\" name=\"$field\" id=\"$value\">
                        //     <label class=\"form-check-label\" for=\"$value\">
                        //     $value
                        //     </label>
                        // </div>";

                        // radio
                        $lists .= str_replace(
                            [
                                '{{fieldSnakeCase}}',
                                '{{optionKebabCase}}',
                                '{{optionUcWords}}',
                                '{{optionLowerCase}}',
                                '{{checked}}'
                            ],
                            [
                                $fieldSnakeCase,
                                GeneratorUtils::singularKebabCase($value),
                                GeneratorUtils::cleanSingularUcWords($value),
                                GeneratorUtils::cleanSingularLowerCase($value),
                                "{{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$field == '$value' ? 'checked' : (old('$field') == '$value' ? 'checked' : '') }}"
                            ],
                            GeneratorUtils::getTemplate('views/forms/radio')
                        );
                    }

                    $lists .= "\t</div>\n";

                    $template .= $lists;
                }
            } else if ($request['input_types'][$i] == 'textarea') {

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
            } else if ($request['input_types'][$i] == 'file') {

                $template .= str_replace(
                    [
                        '{{modelCamelCase}}',
                        '{{fieldPluralSnakeCase}}',
                        '{{fieldSingularSnakeCase}}',
                        '{{fieldUcWords}}',
                        '{{nullable}}'
                    ],
                    [
                        $modelNameSingularCamelCase,
                        GeneratorUtils::pluralSnakeCase($field),
                        GeneratorUtils::singularSnakeCase($field),
                        $fieldUcWords,
                        isset($request['requireds'][$i]) ? ' required' : '',
                    ],
                    GeneratorUtils::getTemplate('views/forms/image')
                );
            } else {

                // input
                $fieldSnakeCase = $fieldSnakeCase;

                $formatValue = "{{ isset($$modelNameSingularCamelCase) ? $$modelNameSingularCamelCase->$fieldSnakeCase : old('$fieldSnakeCase') }}";

                if ($request['data_types'][$i] == 'dateTime') {
                    $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? date('Y-m-d\TH:i', strtotime($" . $modelNameSingularCamelCase . "->$fieldSnakeCase)) : old('$fieldSnakeCase') }}";
                } elseif ($request['data_types'][$i] == 'date') {
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
                        $request['input_types'][$i],
                        $formatValue,
                        isset($request['requireds'][$i]) ? ' required' : '',
                    ],
                    GeneratorUtils::getTemplate('views/forms/input')
                );
            }
        }

        $template .= "</div>";

        // dd($template);

        GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralKebabCase/include"));

        GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralKebabCase/include/form.blade.php"), $template);
    }
}
