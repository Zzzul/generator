<?php

namespace App\Generators;

class GenerateController
{
    /**
     * Generate a controller file
     * @param array $request
     * @return void
     */
    public function execute(array $request)
    {
        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($request['model']);
        $modelNamePluralCamelCase = GeneratorUtils::pluralCamelCase($request['model']);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($request['model']);
        $modelNameSpaceLowercase = GeneratorUtils::cleanSingularLowerCase($request['model']);
        $modelNameSingularPascalCase = GeneratorUtils::singularPascalCase($request['model']);

        $template = "";
        $indexTemplate = "";
        $storeTemplate = "";
        $updateTemplate = "";
        $deleteTemplate = "";

        foreach ($request['input_types'] as $i => $input) {
            if ($input == 'file') {
                $indexTemplate .= $this->uploadFileCode($request['fields'][$i], 'index');

                $storeTemplate .= $this->uploadFileCode($request['fields'][$i], 'store');

                $updateTemplate .= $this->uploadFileCode($request['fields'][$i], 'update', $modelNameSingularCamelCase);

                $deleteTemplate .= $this->uploadFileCode($request['fields'][$i], 'delete', $modelNameSingularCamelCase);
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
                    '{{indexFile}}',
                    '{{storeFile}}',
                    '{{updateFile}}',
                    '{{deleteFile}}'
                ],
                [
                    $modelNameSingularPascalCase,
                    $modelNameSingularCamelCase,
                    $modelNamePluralCamelCase,
                    $modelNamePluralKebabCase,
                    $modelNameSpaceLowercase,
                    $indexTemplate,
                    $storeTemplate,
                    $updateTemplate,
                    $deleteTemplate,
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
                    '{{modelNameSpaceLowercase}}'
                ],
                [
                    $modelNameSingularPascalCase,
                    $modelNameSingularCamelCase,
                    $modelNamePluralCamelCase,
                    $modelNamePluralKebabCase,
                    $modelNameSpaceLowercase
                ],
                GeneratorUtils::getTemplate('controllers/controller')
            );
        }

        GeneratorUtils::generateTemplate(app_path("/Http/Controllers/{$modelNameSingularPascalCase}Controller.php"), $template);
    }

    /**
     * Generate upload file code
     * @param string $field,
     * @param string $path,
     * @param string $model,
     * @return string
     */
    protected function uploadFileCode(string $field, string $path, string $model = null): string
    {
        $replaceString = [
            '{{fieldSingularSnakeCase}}',
            '{{fieldPluralKebabCase}}',
        ];

        $replaceWith = [
            GeneratorUtils::singularSnakeCase($field),
            GeneratorUtils::pluralKebabCase($field),
        ];

        if ($model) {
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
