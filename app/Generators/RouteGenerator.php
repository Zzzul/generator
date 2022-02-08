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
    public function execute($request)
    {
        $modelNameSingularPascalCase = GeneratorUtils::singularPascalCase($request['model']);
        $modelNamePluralLowercase = GeneratorUtils::pluralKebabCase($request['model']);

        $controllerClass = "\n\n" . "Route::resource('" . $modelNamePluralLowercase . "', App\Http\Controllers\\" . $modelNameSingularPascalCase . "Controller::class)->middleware('auth');";

        File::append(base_path('routes/web.php'), $controllerClass);
    }
}
