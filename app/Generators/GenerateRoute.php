<?php

namespace App\Generators;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class GenerateRoute
{
    public function execute($request)
    {
        $modelNameSingularUppercase = Str::singular(ucfirst($request['model']));
        $modelNamePluralLowercase = Str::plural(strtolower($request['model']), 2);

        $controllerClass = "\n\n" . "Route::resource('" . $modelNamePluralLowercase . "', App\Http\Controllers\\" . $modelNameSingularUppercase . "Controller::class)->middleware('auth');";

        File::append(base_path('routes/web.php'), $controllerClass);
    }
}
