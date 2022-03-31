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
    public function generate(array $request)
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
                $fields .= "'" . str()->snake($value) . "', ";
            } else {
                $fields .= "'" . str()->snake($value) . "']";
            }

            if ($request['data_types'][$i] == 'date') {
                $casts .= "'" . str()->snake($value) . "' => 'date:d/m/Y', ";
            } elseif ($request['data_types'][$i] == 'dateTime') {
                $casts .= "'" . str()->snake($value) . "' => 'datetime:d/m/Y H:i', ";
            } elseif (str_contains($request['data_types'][$i], 'integer')) {
                $casts .= "'" . str()->snake($value) . "' => 'integer', ";
            } elseif ($request['data_types'][$i] == 'float') {
                $casts .= "'" . str()->snake($value) . "' => 'float', ";
            } elseif ($request['data_types'][$i] == 'boolean') {
                $casts .= "'" . str()->snake($value) . "' => 'boolean', ";
            } elseif ($request['data_types'][$i] == 'double') {
                $casts .= "'" . str()->snake($value) . "' => 'double', ";
            } elseif (str_contains($request['data_types'][$i], 'string') || str_contains($request['data_types'][$i], 'text') || str_contains($request['data_types'][$i], 'char')) {
                $casts .= "'" . str()->snake($value) . "' => 'string', ";
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
                $relations .= "public function " . str()->snake($constrainName) . "()\n\t{\n\t\treturn \$this->belongsTo(" . $constrainPath . "::class" . $foreign_id . ");\n\t}";

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

            file_put_contents($fullPath . "/$model.php", $template);
        } else {
            file_put_contents(app_path("/Models/$model.php"), $template);
        }
    }
}
