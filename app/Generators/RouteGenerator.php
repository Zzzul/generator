<?php

namespace App\Generators;

use Illuminate\Support\Facades\File;

class RouteGenerator
{
    /**
     * Generate a route on web.php.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $model = GeneratorUtils::setModelName($request['model']);
        $path = GeneratorUtils::getModelLocation($request['model']);

        $modelNameSingularPascalCase = GeneratorUtils::singularPascalCase($model);
        $modelNamePluralLowercase = GeneratorUtils::pluralKebabCase($model);

        if ($path != '') {
            $controllerClass = "\n\n" . "Route::resource('" . $modelNamePluralLowercase . "', App\Http\Controllers\\" . str_replace('/', '\\', $path) . "\\" . $modelNameSingularPascalCase . "Controller::class)->middleware('auth');";
        } else {
            $controllerClass = "\n\n" . "Route::resource('" . $modelNamePluralLowercase . "', App\Http\Controllers\\" . $modelNameSingularPascalCase . "Controller::class)->middleware('auth');";
        }

        File::append(base_path('routes/web.php'), $controllerClass);
    }
}
