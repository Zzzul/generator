<?php

namespace App\Generators;

use Illuminate\Support\Facades\Schema;

class ViewComposerGenerator
{
    /**
     * Generate view composer on viewServiceProvider, if any belongsTo relation.
     *
     * @param array $request
     * @return void
     */
    public function execute(array $request)
    {
        $template = "";

        foreach ($request['data_types'] as $i => $dataType) {
            if ($dataType == 'foreignId') {
                $table = GeneratorUtils::pluralSnakeCase($request['constrains'][$i]);

                $allColums = Schema::getColumnListing($table);

                if (sizeof($allColums) > 0) {
                    $fieldsSelect = "'id', '$allColums[1]'";
                } else {
                    $fieldsSelect = "'id'";
                }

                if ($i > 1) {
                    $template .= "\t\t";
                }

                $template .= str_replace(
                    [
                        '{{modelNamePluralKebabCase}}',
                        '{{constrainsPluralCamelCase}}',
                        '{{constrainsSingularPascalCase}}',
                        '{{fieldsSelect}}'
                    ],
                    [
                        GeneratorUtils::pluralKebabCase($request['model']),
                        GeneratorUtils::pluralCamelCase($request['constrains'][$i]),
                        GeneratorUtils::singularPascalCase($request['constrains'][$i]),
                        $fieldsSelect
                    ],
                    GeneratorUtils::getTemplate('view-composer')
                );
            }
        }

        $template .= "\t\t// don`t remove this comment, it will generate view composer";

        $path = app_path('Providers/ViewServiceProvider.php');
        $viewProviderFile = file_get_contents($path);

        $viewProviderTemplate = str_replace(
            '// don`t remove this comment, it will generate view composer',
            $template,
            $viewProviderFile
        );

        GeneratorUtils::generateTemplate($path, $viewProviderTemplate);
    }
}
