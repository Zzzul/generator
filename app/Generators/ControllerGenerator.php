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
    public function execute(array $request)
    {
        $model = GeneratorUtils::setModelName($request['model']);
        $path = GeneratorUtils::getModelLocation($request['model']);

        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($model);
        $modelNamePluralCamelCase = GeneratorUtils::pluralCamelCase($model);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($model);
        $modelNameSpaceLowercase = GeneratorUtils::cleanSingularLowerCase($model);
        $modelNameSingularPascalCase = GeneratorUtils::singularPascalCase($model);

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
        } else {
            $namespace = "namespace App\Http\Controllers;\n\n";
        }

        foreach ($request['input_types'] as $i => $input) {
            if ($input == 'file') {
                $indexCode .= $this->uploadFileCode($request['fields'][$i], 'index');

                $storeCode .= $this->uploadFileCode($request['fields'][$i], 'store');

                $updateCode .= $this->uploadFileCode($request['fields'][$i], 'update', $modelNameSingularCamelCase);

                $deleteCode .= $this->uploadFileCode($request['fields'][$i], 'delete', $modelNameSingularCamelCase);
            }
        }

        // load the relations for create, show, and edit
        if (in_array('foreignId', $request['data_types'])) {
            $relations .= "$" . $modelNameSingularCamelCase . "->load(";

            $countForeidnId = count(array_keys($request['data_types'], 'foreignId'));

            $query = "$modelNameSingularPascalCase::with(";

            foreach ($request['constrains'] as $i => $constrain) {
                if ($constrain != null) {
                    $constrainSnakeCase = GeneratorUtils::singularSnakeCase($constrain);
                    $selectedColumns = GeneratorUtils::selectColumnAfterIdAndIdItself($constrain);
                    $columnAfterId = GeneratorUtils::getColumnAfterId($constrain);

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
             * with upload file controller file
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
                    '{{modelPath}}',
                    '{{namespace}}',
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
                    $path != '' ? "App\Models\\$path\\$modelNameSingularPascalCase" : "App\Models\\$modelNameSingularPascalCase",
                    $namespace,
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
                    '{{modelPath}}',
                    '{{namespace}}',
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
                    $path != '' ? "App\Models\\$path\\$modelNameSingularPascalCase" : "App\Models\\$modelNameSingularPascalCase",
                    $namespace
                ],
                GeneratorUtils::getTemplate('controllers/controller')
            );
        }

        dd($template);

        GeneratorUtils::generateTemplate(app_path("/Http/Controllers/{$modelNameSingularPascalCase}Controller.php"), $template);
    }

    /**
     * Generate an upload file code.
     *
     * @param string $field,
     * @param string $path,
     * @param null|string $model,
     * @return string
     */
    protected function uploadFileCode(string $field, string $path, ?string $model = null)
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
