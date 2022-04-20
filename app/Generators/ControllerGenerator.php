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

        $query = "$modelNameSingularPascalCase::query()";

        if ($path != '') {
            /**
             * Will generate something like:
             *
             * namespace App\Http\Controllers\Inventory;
             *
             * use App\Http\Controllers\Controller;
             */
            $namespace = "namespace App\Http\Controllers\\$path;\n\nuse App\Http\Controllers\Controller;";

            /**
             * Will generate something like:
             *
             * use App\Http\Requests\Inventory\{StoreProductRequest, UpdateProductRequest};
             */
            $requestPath = "App\Http\Requests\\" . $path . "\{Store" . $modelNameSingularPascalCase . "Request, Update" . $modelNameSingularPascalCase . "Request}";
        } else {
            $namespace = "namespace App\Http\Controllers;\n\n";

            /**
             * will generate something like:
             * use App\Http\Requests\{StoreProductRequest, UpdateProductRequest};
             */
            $requestPath = "App\Http\Requests\{Store" . $modelNameSingularPascalCase . "Request, Update" . $modelNameSingularPascalCase . "Request}";
        }

        $relations = "";
        $addColumns = "";

        if (in_array('text', $request['column_types']) || in_array('longText', $request['column_types'])) {
            $limitText = config('generator.format.limit_text') ? config('generator.format.limit_text') : 200;

            foreach($request['column_types'] as $i => $type){
                if ($type == 'text' || $type == 'longText') {
                    $addColumns .= "->addColumn('" . str($request['fields'][$i])->snake() . "', function(\$row){
                    return str(\$row->" . str($request['fields'][$i])->snake() . ")->limit($limitText);
                })\n\t\t\t\t";
                }
            }
        }

        // load the relations for create, show, and edit
        if (in_array('foreignId', $request['column_types'])) {

            $relations .= "$" . $modelNameSingularCamelCase . "->load(";

            $countForeidnId = count(array_keys($request['column_types'], 'foreignId'));

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
                        /**
                         * Will generate something like:
                         *
                         * 'category:id,name',
                         */
                        $relations .= ", ";
                        $query .= "'$constrainSnakeCase:$selectedColumns', ";
                    } else {
                        /**
                         * Will generate something like:
                         *
                         * 'category:id,name');
                         */
                        $relations .= ");\n\n\t\t";
                        $query .= "'$constrainSnakeCase:$selectedColumns')";
                    }

                    /**
                     * Will generate something like:
                     *
                     * ->addColumn('category', function($row){
                     *     return $row->category ? $row->category->name : '-';
                     * })
                     */
                    $addColumns .= "->addColumn('$constrainSnakeCase', function (\$row) {
                    return \$row->" . $constrainSnakeCase . " ? \$row->" . $constrainSnakeCase . "->$columnAfterId : '-';
                })";
                }
            }
        }

        if (in_array('file', $request['input_types'])) {
            $indexCode = "";
            $storeCode = "";
            $updateCode = "";
            $deleteCode = "";

            foreach ($request['input_types'] as $i => $input) {
                if ($input == 'file') {
                    $indexCode .= $this->generateUploadFileCode($request['fields'][$i], 'index');

                    $storeCode .= $this->generateUploadFileCode($request['fields'][$i], 'store');

                    $updateCode .= $this->generateUploadFileCode($request['fields'][$i], 'update', $modelNameSingularCamelCase);

                    $deleteCode .= $this->generateUploadFileCode($request['fields'][$i], 'delete', $modelNameSingularCamelCase);
                }
            }

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
                    '{{modelNameSingularUcWords}}',
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
                    $modelNameSingularUcWords,
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
            '{{fieldSnakeCase}}',
            '{{fieldPluralSnakeCase}}',
            '{{fieldPluralKebabCase}}',
            '{{defaultImage}}',
            '{{uploadPath}}',
            '{{uploadPathPublic}}',
            '{{width}}',
            '{{height}}',
            '{{aspectRatio}}',
        ];

        $replaceWith = [
            GeneratorUtils::singularSnakeCase($field),
            GeneratorUtils::pluralSnakeCase($field),
            GeneratorUtils::pluralKebabCase($field),
            config('generator.image.default') ? config('generator.image.default') : 'https://via.placeholder.com/350?text=No+Image+Avaiable',
            config('generator.image.path') == 'storage' ? "storage_path('app/public/uploads" : "public_path('uploads",
            config('generator.image.path') == 'storage' ? "storage/uploads" : "uploads",
            is_int(config('generator.image.width')) ? config('generator.image.width') : 500,
            is_int(config('generator.image.height')) ? config('generator.image.height') : 500,
            config('generator.image.aspect_ratio') ? "\n\t\t\t\t\$constraint->aspectRatio();" : '',
        ];

        if ($model != null) {
            array_push($replaceString, '{{modelNameSingularCamelCase}}');
            array_push($replaceWith, $model);
        }

        if (config('generator.image.crop')) {
            return str_replace(
                $replaceString,
                $replaceWith,
                GeneratorUtils::getTemplate("controllers/upload-files/with-crop/$path")
            );
        } else {
            return str_replace(
                $replaceString,
                $replaceWith,
                GeneratorUtils::getTemplate("controllers/upload-files/$path")
            );
        }
    }
}
