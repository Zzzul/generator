<?php

namespace App\Generators;

class ModelGenerator
{
    /**
     * Generate a model file
     *
     * @param array $request
     * @return void
     */
    public function execute($request)
    {
        $model = GeneratorUtils::singularPascalCase($request['model']);

        $fields = "[";
        $casts = "[";
        $relations = "";
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
            } elseif (str_contains($request['data_types'][$i], 'integer')) {
                $casts .= "'" . GeneratorUtils::singularSnakeCase($value) . "' => 'integer', ";
            } elseif ($request['data_types'][$i] == 'float') {

                $casts .= "'" . GeneratorUtils::singularSnakeCase($value) . "' => 'float', ";
            } elseif ($request['data_types'][$i] == 'boolean') {
                $casts .= "'" . GeneratorUtils::singularSnakeCase($value) . "' => 'boolean', ";
            } elseif ($request['data_types'][$i] == 'double') {
                $casts .= "'" . GeneratorUtils::singularSnakeCase($value) . "' => 'double', ";
            } elseif (str_contains($request['data_types'][$i], 'string') || str_contains($request['data_types'][$i], 'text') || str_contains($request['data_types'][$i], 'char')) {
                $casts .= "'" . GeneratorUtils::singularSnakeCase($value) . "' => 'string', ";
            }

            if ($request['data_types'][$i] == 'foreignId') {
                $foreign_id = isset($request['foreign_ids'][$i]) ? ", '" . $request['foreign_ids'][$i] . "'" : '';

                if ($i > 0) {
                    $relations .= "\t";
                }

                $relations .= "public function " . GeneratorUtils::singularSnakeCase($request['constrains'][$i]) . "()\n\t{\n\t\treturn \$this->belongsTo(" . GeneratorUtils::singularPascalCase($request['constrains'][$i]) . "::class" . $foreign_id . ");\n\t}";

                if ($i + 1 != $totalFields) {
                    $relations .= "\n\n";
                }
            }
        }

        $casts .= "'created_at' => 'datetime:d/m/Y H:i', 'updated_at' => 'datetime:d/m/Y H:i']";

        $template = str_replace(
            [
                '{{modelName}}',
                '{{fields}}',
                '{{casts}}',
                '{{relations}}'
            ],
            [
                $model,
                $fields,
                $casts,
                $relations
            ],
            GeneratorUtils::getTemplate('model')
        );

        GeneratorUtils::generateTemplate(app_path("/Models/$model.php"), $template);
    }
}
