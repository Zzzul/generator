<?php

namespace App\Generators;

use Illuminate\Support\Str;

class GenerateController
{
    public function execute($request)
    {
        $modelNameUppercase = Str::singular(ucwords($request['model']));
        $modelNameLowercase = Str::singular(strtolower($request['model']));
        $modelNamePluralLowerCase = Str::plural(strtolower($request['model']), 2);

        $template = str_replace(
            [
                '{{modelNameUppercase}}',
                '{{modelNameLowercase}}',
                '{{modelNamePluralLowercase}}'
            ],
            [
                $modelNameUppercase,
                $modelNameLowercase,
                $modelNamePluralLowerCase
            ],
            GeneratorUtils::getTemplate('controller')
        );

        GeneratorUtils::generateTemplate(app_path("/Http/Controllers/{$modelNameUppercase}Controller.php"), $template);
    }
}
