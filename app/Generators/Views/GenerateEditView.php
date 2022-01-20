<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;
use Illuminate\Support\Str;

class GenerateEditView
{
    public function execute(array $request)
    {
        $modelNamePluralUppercase = Str::plural(ucfirst($request['model']), 2);

        $modelNamePluralLowercase = Str::plural(strtolower($request['model']), 2);
        $modelNameSingularLowercase = Str::singular(strtolower($request['model']));

        $template = str_replace(
            [
                '{{modelNamePluralUppercase}}',
                '{{modelNameSingularLowercase}}',
                '{{modelNamePluralLowercase}}'
            ],
            [
                $modelNamePluralUppercase,
                $modelNameSingularLowercase,
                $modelNamePluralLowercase
            ],
            GeneratorUtils::getTemplate('views/edit')
        );

        GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralLowercase"));

        GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralLowercase/edit.blade.php"), $template);
    }
}
