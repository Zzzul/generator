<?php

namespace App\Generators;

class ControllerGenerator
{
    /**
     * Generate a controller file.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $model = GeneratorUtils::setModelName($request['model']);
        $path = GeneratorUtils::getModelLocation($request['model']);

        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($model);
        $modelNamePluralCamelCase = GeneratorUtils::pluralCamelCase($model);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($model);
        $modelNameSpaceLowercase = GeneratorUtils::cleanSingularLowerCase($model);
        $modelNameSingularPascalCase = GeneratorUtils::singularPascalCase($model);
        $modelNameSingularUcWords = GeneratorUtils::cleanSingularUcWords($model);

        $template = "";
        $indexCode = "";
        $storeCode = "";
        $updateCode = "";
        $deleteCode = "";
        $relations = "";
        $addColumns = "";
        $query = "$modelNameSingularPascalCase::query()";

        if ($path != '') {
            $namespace = "namespace App\Http\Controllers\\$path;\n\nuse App\Http\Controllers\Controller;";

            $requestPath = "App\Http\Requests\\" . $path . "\{Store" . $modelNameSingularPascalCase . "Request, Update" . $modelNameSingularPascalCase . "Request}";
        } else {
            $namespace = "namespace App\Http\Controllers;\n\n";

            /**
             * will generate something like:
             * use App\Http\Requests\{StoreProductRequest, UpdateProductRequest};
             */
            $requestPath = "App\Http\Requests\{Store" . $modelNameSingularPascalCase . "Request, Update" . $modelNameSingularPascalCase . "Request}";
        }

        foreach ($request['input_types'] as $i => $input) {
            if ($input == 'file') {
                $indexCode .= $this->generateUploadFileCode($request['fields'][$i], 'index');

                $storeCode .= $this->generateUploadFileCode($request['fields'][$i], 'store');

                $updateCode .= $this->generateUploadFileCode($request['fields'][$i], 'update', $modelNameSingularCamelCase);

                $deleteCode .= $this->generateUploadFileCode($request['fields'][$i], 'delete', $modelNameSingularCamelCase);
            }
        }

        // load the relations for create, show, and edit
        if (in_array('foreignId', $request['data_types'])) {
            $relations .= "$" . $modelNameSingularCamelCase . "->load(";

            $countForeidnId = count(array_keys($request['data_types'], 'foreignId'));

            $query = "$modelNameSingularPascalCase::with(";

            foreach ($request['constrains'] as $i => $constrain) {
                if ($constrain != null) {
                    // remove path or '/' if exists
                    $constrainName = GeneratorUtils::setModelName($request['constrains'][$i]);

                    $constrainSnakeCase = GeneratorUtils::singularSnakeCase($constrainName);
                    $selectedColumns = GeneratorUtils::selectColumnAfterIdAndIdItself($constrainName);
                    $columnAfterId = GeneratorUtils::getColumnAfterId($constrainName);

                    $relations .= "'$constrainSnakeCase:$selectedColumns'";

                    if ($i + 1 < $countForeidnId) {
                        $relations .= ", ";
                        $query .= "'$constrainSnakeCase:$selectedColumns', ";
                    } else {
                        $relations .= ");\n\n\t\t";
                        $query .= "'$constrainSnakeCase:$selectedColumns')";
                    }

                    $addColumns .= "->addColumn('$constrainSnakeCase', function (\$row) {
                    return \$row->" . $constrainSnakeCase . " ? \$row->" . $constrainSnakeCase . "->$columnAfterId : '';
                })";
                }
            }
        }

        if (in_array('file', $request['input_types'])) {
            /**
             * controller with upload file code
             */
            $template = str_replace(
                [
                    '{{modelNameSingularPascalCase}}',
                    '{{modelNameSingularCamelCase}}',
                    '{{modelNamePluralCamleCase}}',
                    '{{modelNamePluralKebabCase}}',
                    '{{modelNameSpaceLowercase}}',
                    '{{indexCode}}',
                    '{{storeCode}}',
                    '{{updateCode}}',
                    '{{deleteCode}}',
                    '{{loadRelation}}',
                    '{{addColumns}}',
                    '{{query}}',
                    '{{namespace}}',
                    '{{requestPath}}',
                    '{{modelPath}}',
                    '{{viewPath}}',
                    '{{modelNameSingularUcWords}}'
                ],
                [
                    $modelNameSingularPascalCase,
                    $modelNameSingularCamelCase,
                    $modelNamePluralCamelCase,
                    $modelNamePluralKebabCase,
                    $modelNameSpaceLowercase,
                    $indexCode,
                    $storeCode,
                    $updateCode,
                    $deleteCode,
                    $relations,
                    $addColumns,
                    $query,
                    $namespace,
                    $requestPath,
                    $path != '' ? "App\Models\\$path\\$modelNameSingularPascalCase" : "App\Models\\$modelNameSingularPascalCase",
                    $path != '' ? str_replace('\\', '.', strtolower($path)) . "." : '',
                    $modelNameSingularUcWords
                ],
                GeneratorUtils::getTemplate('controllers/controller-with-upload-file')
            );
        } else {
            /**
             * default controller
             */
            $template = str_replace(
                [
                    '{{modelNameSingularPascalCase}}',
                    '{{modelNameSingularCamelCase}}',
                    '{{modelNamePluralCamelCase}}',
                    '{{modelNamePluralKebabCase}}',
                    '{{modelNameSpaceLowercase}}',
                    '{{loadRelation}}',
                    '{{addColumns}}',
                    '{{query}}',
                    '{{namespace}}',
                    '{{requestPath}}',
                    '{{modelPath}}',
                    '{{viewPath}}',
                    '{{modelNameSingularUcWords}}'
                ],
                [
                    $modelNameSingularPascalCase,
                    $modelNameSingularCamelCase,
                    $modelNamePluralCamelCase,
                    $modelNamePluralKebabCase,
                    $modelNameSpaceLowercase,
                    $relations,
                    $addColumns,
                    $query,
                    $namespace,
                    $requestPath,
                    $path != '' ? "App\Models\\$path\\$modelNameSingularPascalCase" : "App\Models\\$modelNameSingularPascalCase",
                    $path != '' ? str_replace('\\', '.', strtolower($path)) . "." : '',
                    $modelNameSingularUcWords
                ],
                GeneratorUtils::getTemplate('controllers/controller')
            );
        }

        if ($path != '') {
            $fullPath = app_path("/Http/Controllers/$path/");

            GeneratorUtils::checkFolder($fullPath);

            file_put_contents("$fullPath" . $modelNameSingularPascalCase . "Controller.php", $template);
        } else {
            file_put_contents(app_path("/Http/Controllers/{$modelNameSingularPascalCase}Controller.php"), $template);
        }
    }

    /**
     * Generate an upload file code.
     *
     * @param string $field,
     * @param string $path,
     * @param null|string $model,
     * @return string
     */
    protected function generateUploadFileCode(string $field, string $path, null|string $model = null)
    {
        $replaceString = [
            '{{fieldSingularSnakeCase}}',
            '{{fieldPluralKebabCase}}',
        ];

        $replaceWith = [
            GeneratorUtils::singularSnakeCase($field),
            GeneratorUtils::pluralKebabCase($field),
        ];

        if ($model != null) {
            array_push($replaceString, '{{modelNameSingularCamelCase}}');
            array_push($replaceWith, $model);
        }

        return str_replace(
            $replaceString,
            $replaceWith,
            GeneratorUtils::getTemplate("controllers/upload-files/$path")
        );
    }
}
