<?php

namespace App\Generators;

class GenerateModel
{
    public function execute($request)
    {
        $model = GeneratorUtils::singularPascalCase($request['model']);

        $fields = [];

        foreach ($request['fields'] as $field) {
            $fields[] = GeneratorUtils::singularSnakeCase($field);
        }

        $template = str_replace(
            [
                '{{modelName}}',
                '{{fields}}'
            ],
            [
                $model,
                json_encode($fields)
            ],
            GeneratorUtils::getTemplate('model')
        );

        GeneratorUtils::generateTemplate(app_path("/Models/$model.php"), $template);
    }
}
