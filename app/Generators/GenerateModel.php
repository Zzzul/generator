<?php

namespace App\Generators;

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
        $totalFields = count($request['fields']);

        foreach ($request['fields'] as $key => $value) {
            if ($key + 1 != $totalFields) {
                $fields .= "'" . GeneratorUtils::singularSnakeCase($value) . "', ";
            } else {
                $fields .= "'" . GeneratorUtils::singularSnakeCase($value) . "']";
            }
        }

        $template = str_replace(
            [
                '{{modelName}}',
                '{{fields}}'
            ],
            [
                $model,
                $fields
            ],
            GeneratorUtils::getTemplate('model')
        );

        GeneratorUtils::generateTemplate(app_path("/Models/$model.php"), $template);
    }
}
