<?php

namespace App\Generators;

use Illuminate\Support\Str;

class GenerateModel
{
    public function execute($request)
    {
        $model = ucfirst(Str::camel(Str::singular($request['model'])));

        $fields = [];

        foreach ($request['fields'] as $field) {
            $fields[] = Str::snake(strtolower($field));
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
