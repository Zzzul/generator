<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

class GenerateFormView
{
    /**
     * Generate a form/input for create and edit
     * @param array $request
     * @return void
     */
    public function execute(array $request)
    {
        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($request['model']);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($request['model']);

        $template = "<div class=\"row mb-2\">\n";

        foreach ($request['fields'] as $i => $field) {

            $fieldSnakeCase = GeneratorUtils::singularSnakeCase($field);
            $fieldUcWords = GeneratorUtils::cleanSingularUcWords($field);

            if ($request['data_types'][$i] == 'enum') {
                $options = "";

                $arrOption = explode('|', $request['select_options'][$i]);

                $totalOptions = count($arrOption);

                if ($request['input_types'][$i] == 'select') {

                    // select
                    foreach ($arrOption as $i => $value) {
                        $options .= "<option value=\"" . $value . "\" {{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '" . $value . "' ? 'selected' : (old('$fieldSnakeCase') == '" . $value . "' ? 'selected' : '') }}>" . GeneratorUtils::cleanSingularUcWords($value) . "</option>";

                        if ($i + 1 != $totalOptions) {
                            $options .= "\n\t\t";
                        } else {
                            $options .= "\t\t\t";
                        }
                    }

                    $template .= str_replace(
                        [
                            '{{fieldLowercase}}',
                            '{{fieldUppercase}}',
                            '{{fieldSpaceLowercase}}',
                            '{{options}}',
                            '{{nullable}}'
                        ],
                        [
                            $fieldSnakeCase,
                            $fieldUcWords,
                            GeneratorUtils::cleanSingularLowerCase($field),
                            $options,
                            $request['requireds'][$i] == 'yes' ? ' required' : '',
                        ],
                        GeneratorUtils::getTemplate('views/forms/select')
                    );
                } else {

                    // radio
                    $options .= "\t<div class=\"col-md-6\">\n\t<label class=\"text-dark\">$fieldUcWords</label>\n";

                    foreach ($arrOption as $i => $value) {
                        $options .= str_replace(
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

                    $options .= "\t</div>\n";

                    $template .= $options;
                }
            } else if ($request['data_types'][$i] == 'boolean') {
                $options = "<option value=\"0\" {{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '0' ? 'selected' : (old('$fieldSnakeCase') == '0' ? 'selected' : '') }}>{{ __('True') }}</option>\n\t\t\t\t<option value=\"1\" {{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '1' ? 'selected' : (old('$fieldSnakeCase') == '1' ? 'selected' : '') }}>{{ __('False') }}</option>";

                $template .= str_replace(
                    [
                        '{{fieldLowercase}}',
                        '{{fieldUppercase}}',
                        '{{fieldSpaceLowercase}}',
                        '{{options}}',
                        '{{nullable}}'
                    ],
                    [
                        $fieldSnakeCase,
                        $fieldUcWords,
                        GeneratorUtils::cleanSingularLowerCase($field),
                        $options,
                        $request['requireds'][$i] == 'yes' ? ' required' : '',
                    ],
                    GeneratorUtils::getTemplate('views/forms/select')
                );
            } else if ($request['input_types'][$i] == 'textarea') {

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
                        $request['requireds'][$i] == 'yes' ? ' required' : '',
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
                        $request['requireds'][$i] == 'yes' ? ' required' : '',
                    ],
                    GeneratorUtils::getTemplate('views/forms/image')
                );
            } else {

                // input
                $fieldSnakeCase = $fieldSnakeCase;

                $formatValue = "{{ isset($$modelNameSingularCamelCase) ? $$modelNameSingularCamelCase->$fieldSnakeCase : old('$fieldSnakeCase') }}";

                if ($request['data_types'][$i] == 'dateTime') {
                    $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('Y-m-d\TH:i') : old('$fieldSnakeCase') }}";
                } elseif ($request['data_types'][$i] == 'date') {
                    $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('Y-m-d') : old('$fieldSnakeCase') }}";
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
                        $request['requireds'][$i] == 'yes' ? ' required' : '',
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
