<?php

namespace App\Generators;

class ModelGenerator
{
    /**
     * Generate a model file.
     *
     * @param array $request
     * @return void
     */
    public function execute(array $request)
    {
        $path = GeneratorUtils::getModelLocation($request['model']);
        $model = GeneratorUtils::setModelName($request['model']);

        $fields = "[";
        $casts = "[";
        $relations = "";
        $totalFields = count($request['fields']);

        if ($path != '') {
            $namespace = "namespace App\\Models\\$path;";
        } else {
            $namespace = "namespace App\\Models;";
        }

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
            } elseif ($request['data_types'][$i] == 'foreignId') {
                $constrainPath = GeneratorUtils::getModelLocation($request['constrains'][$i]);
                $constrainName = GeneratorUtils::setModelName($request['constrains'][$i]);

                $foreign_id = isset($request['foreign_ids'][$i]) ? ", '" . $request['foreign_ids'][$i] . "'" : '';

                if ($i > 0) {
                    $relations .= "\t";
                }

                /**
                 * will generate something like:
                 * \App\Models\Master\Product::class or \App\Models\Product::class
                 */
                if ($constrainPath != '') {
                    $constrainPath = "\\App\\Models\\$constrainPath\\$constrainName";
                } else {
                    $constrainPath = "\\App\\Models\\$constrainName";
                }

                /**
                 * will generate something like:
                 *
                 * public function product()
                 * {
                 *     return $this->belongsTo(\App\Models\Master\Product::class); or return $this->belongsTo(\App\Models\Product::class);
                 * }
                 */
                $relations .= "public function " . GeneratorUtils::singularSnakeCase($constrainName) . "()\n\t{\n\t\treturn \$this->belongsTo(" . $constrainPath . "::class" . $foreign_id . ");\n\t}";

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
                '{{relations}}',
                '{{namespace}}'
            ],
            [
                $model,
                $fields,
                $casts,
                $relations,
                $namespace
            ],
            GeneratorUtils::getTemplate('model')
        );

        if ($path != '') {
            $fullPath = app_path("/Models/$path");

            GeneratorUtils::checkFolder($fullPath);
            GeneratorUtils::generateTemplate($fullPath . "/$model.php", $template);
        } else {
            GeneratorUtils::generateTemplate(app_path("/Models/$model.php"), $template);
        }
    }
}
