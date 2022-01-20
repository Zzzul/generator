<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;
use Illuminate\Support\Str;

class GenerateFormView
{
    public function execute(array $request)
    {
        $modelNameSingularLowercase = Str::singular(strtolower($request['model']));
        $modelNamePluralLowercase = Str::plural(strtolower($request['model']), 2);

        $template = '<div class="row mb-2">';

        foreach ($request['fields'] as $i => $field) {

            if ($request['types'][$i] == 'enum') {
                $lists = "";

                $options = explode(';', $request['select_options'][$i]);

                $totalOptions = count($options);

                foreach ($options as $key => $value) {
                    $lists .= "<option value=\"$value\" {{ isset($" . $modelNameSingularLowercase . ") && $" . $modelNameSingularLowercase . "->$field == '$value' ? 'selected' : '' }}>$value</option>";

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
                        Str::snake(strtolower($field)),
                        ucwords(str_replace('_', ' ', $field)),
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
                        Str::snake(strtolower($field)),
                        ucwords($field),
                        $modelNameSingularLowercase,
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
                        Str::snake(strtolower($field)),
                        ucwords(Str::replace('_', ' ', $field)),
                        $modelNameSingularLowercase,
                        GeneratorUtils::setInputType($request['types'][$i]),
                        isset($request['requireds'][$i]) ? ' required' : '',
                    ],
                    GeneratorUtils::getTemplate('views/forms/input')
                );
            }
        }

        $template .= "</div>";

        GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralLowercase/include"));

        GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralLowercase/include/form.blade.php"), $template);
    }
}
