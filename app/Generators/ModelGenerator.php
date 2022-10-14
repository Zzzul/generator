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
        $dateTimeFormat = config('generator.format.datetime') ? config('generator.format.datetime') : 'd/m/Y H:i';
        $protectedHidden = "";

        if (in_array('password', $request['input_types'])) {
            $protectedHidden .= <<<PHP
            /**
                 * The attributes that should be hidden for serialization.
                 *
                 * @var string[]
                */
                protected \$hidden = [
            PHP;
        }

        switch ($path) {
            case '':
                $namespace = "namespace App\\Models;";
                break;
            default:
                $namespace = "namespace App\\Models\\$path;";
                break;
        }

        foreach ($request['fields'] as $i => $field) {
            switch ($i + 1 != $totalFields) {
                case true:
                    $fields .= "'" . str()->snake($field) . "', ";
                    break;
                default:
                    $fields .= "'" . str()->snake($field) . "']";
                    break;
            }

            if ($request['input_types'][$i] == 'password') {
                $protectedHidden .= "'" . str()->snake($field) . "', ";
            }

            switch ($request['column_types'][$i]) {
                case 'date':
                    $dateFormat = config('generator.format.date') ? config('generator.format.date') : 'd/m/Y';
                    $casts .= "'" . str()->snake($field) . "' => 'date:$dateFormat', ";
                    break;
                case 'time':
                    $timeFormat = config('generator.format.time') ? config('generator.format.time') : 'H:i';
                    $casts .= "'" . str()->snake($field) . "' => 'datetime:$timeFormat', ";
                    break;
                case 'year':
                    $casts .= "'" . str()->snake($field) . "' => 'integer', ";
                    break;
                case 'dateTime':
                    $casts .= "'" . str()->snake($field) . "' => 'datetime:$dateTimeFormat', ";
                    break;
                case 'float':
                    $casts .= "'" . str()->snake($field) . "' => 'float', ";
                    break;
                case 'boolean':
                    $casts .= "'" . str()->snake($field) . "' => 'boolean', ";
                    break;
                case 'double':
                    $casts .= "'" . str()->snake($field) . "' => 'double', ";
                    break;
                case 'foreignId':
                    $constrainPath = GeneratorUtils::getModelLocation($request['constrains'][$i]);
                    $constrainName = GeneratorUtils::setModelName($request['constrains'][$i]);

                    $foreign_id = isset($request['foreign_ids'][$i]) ? ", '" . $request['foreign_ids'][$i] . "'" : '';

                    if ($i > 0) {
                        $relations .= "\t";
                    }

                    /**
                     * will generate something like:
                     * \App\Models\Main\Product::class
                     *              or
                     *  \App\Models\Product::class
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
                     *     return $this->belongsTo(\App\Models\Main\Product::class);
                     *                              or
                     *     return $this->belongsTo(\App\Models\Product::class);
                     * }
                     */
                    $relations .= "\n\tpublic function " . str()->snake($constrainName) . "()\n\t{\n\t\treturn \$this->belongsTo(" . $constrainPath . "::class" . $foreign_id . ");\n\t}";

                    // if ($i + 1 != $totalFields) {
                    //     $relations .= "\n";
                    // }
                    break;
                default:
                    break;
            }

            if ($request['input_types'][$i] == 'month') {
                $castFormat = config('generator.format.month') ? config('generator.format.month') : 'M/Y';
                $casts .= "'" . str()->snake($field) . "' => 'datetime:$castFormat', ";
            }

            if ($request['input_types'][$i] == 'week') {
                $castFormat = config('generator.format.week') ? config('generator.format.week') : 'Y-\WW';
                $casts .= "'" . str()->snake($field) . "' => 'datetime:$castFormat', ";
            }

            if (str_contains($request['column_types'][$i], 'integer')) {
                $casts .= "'" . str()->snake($field) . "' => 'integer', ";
            }

            if (str_contains($request['column_types'][$i], 'string') || str_contains($request['column_types'][$i], 'text') || str_contains($request['column_types'][$i], 'char')) {
                $casts .= "'" . str()->snake($field) . "' => 'string', ";
            }
        }

        if($protectedHidden != ""){
            // removoe "', " and then change to "'" in the of array for better code.
            // $protectedHidden  = str_replace("', ", "'", $protectedHidden);
            $protectedHidden = substr($protectedHidden, 0, -2);
            $protectedHidden .= "];";
        }

        $casts .= <<<PHP
        'created_at' => 'datetime:$dateTimeFormat', 'updated_at' => 'datetime:$dateTimeFormat']
        PHP;

        $template = str_replace(
            [
                '{{modelName}}',
                '{{fields}}',
                '{{casts}}',
                '{{relations}}',
                '{{namespace}}',
                '{{protectedHidden}}'
            ],
            [
                $model,
                $fields,
                $casts,
                $relations,
                $namespace,
                $protectedHidden
            ],
            GeneratorUtils::getTemplate('model')
        );

        switch ($path) {
            case '':
                file_put_contents(app_path("/Models/$model.php"), $template);
                break;
            default:
                $fullPath = app_path("/Models/$path");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents($fullPath . "/$model.php", $template);
                break;
        }
    }
}
