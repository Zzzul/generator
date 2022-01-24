<?php

namespace App\Generators;

use Illuminate\Support\Facades\File;

class GenerateRoute
{
    public function execute($request)
    {
        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($request['model']);
        $modelNamePluralLowercase = GeneratorUtils::pluralKebabCase($request['model']);

        $controllerClass = "\n\n" . "Route::resource('" . $modelNamePluralLowercase . "', App\Http\Controllers\\" . $modelNameSingularCamelCase . "Controller::class)->middleware('auth');";

        File::append(base_path('routes/web.php'), $controllerClass);
    }
}