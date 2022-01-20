<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;
use Illuminate\Support\Str;

class GenerateActionView
{
    public function execute($request)
    {
        $modelNamePluralLowercase = Str::plural(strtolower($request['model']), 2);
        $modelNameSingularLowercase = Str::singular(strtolower($request['model']));

        $template = str_replace(
            [
                '{{modelNameSingularLowercase}}',
                '{{modelNamePluralLowercase}}'
            ],
            [
                $modelNameSingularLowercase,
                $modelNamePluralLowercase
            ],
            GeneratorUtils::getTemplate('views/action')
        );

        GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralLowercase/include"));

        GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralLowercase/include/action.blade.php"), $template);
    }
}
