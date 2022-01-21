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

        $template = '<div class="row mb-2">';

        foreach ($request['fields'] as $i => $field) {

            if ($request['types'][$i] == 'enum') {
                $lists = "";

                $options = explode(';', $request['select_options'][$i]);

                $totalOptions = count($options);

                foreach ($options as $key => $value) {
                    $lists .= "<option value=\"$value\" {{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$field == '$value' ? 'selected' : '' }}>$value</option>";

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
                        GeneratorUtils::singularSnakeCase($field),
                        GeneratorUtils::cleanSingularUcWords($field),
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
                        GeneratorUtils::singularSnakeCase($field),
                        GeneratorUtils::cleanSingularUcWords($field),
                        $modelNameSingularCamelCase,
                        isset($request['requireds'][$i]) ? ' required' : '',
                    ],
                    GeneratorUtils::getTemplate('views/forms/textarea')
                );
            } else {

                // input
                $template .= str_replace(
                    [
                        '{{fieldLowercase}}',
                        '{{fieldUppercase}}',
                        '{{modelName}}',
                        '{{type}}',
                        '{{nullable}}'
                    ],
                    [
                        GeneratorUtils::singularSnakeCase($field),
                        GeneratorUtils::cleanSingularUcWords($field),
                        $modelNameSingularCamelCase,
                        GeneratorUtils::setInputType($request['types'][$i], $request['fields'][$i]),
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
