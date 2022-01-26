<?php

namespace App\Generators;

use Illuminate\Support\Str;

class GenerateModel
{
    /**
     * Generate a model file
     * @param array $request
     * @return void
     */
    public function execute($request)
    {
        $model = GeneratorUtils::singularPascalCase($request['model']);

        $fields = "[";
        $casts = "[";
        $totalFields = count($request['fields']);

        foreach ($request['fields'] as $i => $value) {

            if ($i + 1 != $totalFields) {
                $fields .= "'" . GeneratorUtils::singularSnakeCase($value) . "', ";
            } else {
                $fields .= "'" . GeneratorUtils::singularSnakeCase($value) . "']";
            }

            if ($request['data_types'][$i] == 'date') {
                $casts .= "'" . GeneratorUtils::singularSnakeCase($value) . "' => 'date:d/m/Y', ";
            } elseif ($request['data_types'][$i] == 'dateTime') {
                $casts .= "'" . GeneratorUtils::singularSnakeCase($value) . "' => 'datetime:d/m/Y H:i', ";
            } elseif (Str::contains($request['data_types'][$i], 'integer')) {
                $casts .= "'" . GeneratorUtils::singularSnakeCase($value) . "' => 'integer', ";
            } elseif ($request['data_types'][$i] == 'float') {

                $casts .= "'" . GeneratorUtils::singularSnakeCase($value) . "' => 'float', ";
            } elseif ($request['data_types'][$i] == 'boolean') {
                $casts .= "'" . GeneratorUtils::singularSnakeCase($value) . "' => 'boolean', ";
            } elseif ($request['data_types'][$i] == 'double') {
                $casts .= "'" . GeneratorUtils::singularSnakeCase($value) . "' => 'double', ";
            } elseif (Str::contains($request['data_types'][$i], 'string') || Str::contains($request['data_types'][$i], 'text') || Str::contains($request['data_types'][$i], 'char')) {
                $casts .= "'" . GeneratorUtils::singularSnakeCase($value) . "' => 'string', ";
            }
        }

        $casts .= "'created_at' => 'datetime:d/m/Y H:i', 'updated_at' => 'datetime:d/m/Y H:i']";

        $template = str_replace(
            [
                '{{modelName}}',
                '{{fields}}',
                '{{casts}}'
            ],
            [
                $model,
                $fields,
                $casts
            ],
            GeneratorUtils::getTemplate('model')
        );

        // dd($template);

        GeneratorUtils::generateTemplate(app_path("/Models/$model.php"), $template);
    }
}
