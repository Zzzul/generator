<?php

namespace App\Generators;

class GenerateRequest
{
    public function execute($request)
    {
        $model = GeneratorUtils::singularPascalCase($request['model']);

        $validations = '';
        $totalFields = count($request['fields']);

        foreach ($request['fields'] as $i => $field) {
            /**
             * will generate like:
             * 'name' =>
             */
            $validations .= "'" . GeneratorUtils::singularSnakeCase($field) . "' => ";

            /**
             * will generate like:
             * 'name' => 'required
             */
            if (isset($request['requireds'][$i])) {
                $validations .= "'required";
            } else {
                $validations .= "'nullable";
            }

            if ($request['types'][$i] == 'enum') {
                /**
                 * will generate like:
                 * 'name' => 'required|in:water,fire',
                 */
                $in = "|in:";

                $options = explode(';', $request['select_options'][$i]);

                $totalOptions = count($options);

                foreach ($options as $key => $option) {
                    if ($key + 1 != $totalOptions) {
                        $in .= $option . ",";
                    } else {
                        $in .= $option;
                    }
                }

                $validations .= $in;
            }

            if ($i + 1 != $totalFields) {

                if ($request['lengths'][$i] && $request['lengths'][$i] >= 0) {
                    /**
                     * will generate like:
                     * 'name' => 'required|max:30',
                     * with new line and 3x tab
                     */
                    $validations .= "|max:" . $request['lengths'][$i] . "',\n\t\t\t";
                } else {
                    /**
                     * will generate like:
                     * 'name' => 'required',
                     * with new line and 3x tab
                     */
                    $validations .= "',\n\t\t\t";
                }
            } else {
                if ($request['lengths'][$i] && $request['lengths'][$i] >= 0) {
                    /**
                     * will generate like:
                     * 'name' => 'required|max:30',
                     */
                    $validations .= "|max:" . $request['lengths'][$i] . "',";
                } else {
                    /**
                     * will generate like:
                     * 'name' => 'required',
                     */
                    $validations .= "',";
                }
            }
        }

        $storeRequestTemplate = str_replace(
            [
                '{{modelNamePascalCase}}',
                '{{fields}}'
            ],
            [
                "Store$model",
                $validations
            ],
            GeneratorUtils::getTemplate('request')
        );

        $updateRequestTemplate = str_replace(
            [
                '{{modelNamePascalCase}}',
                '{{fields}}'
            ],
            [
                "Update$model",
                $validations
            ],
            GeneratorUtils::getTemplate('request')
        );

        GeneratorUtils::checkFolder(app_path('/Http/Requests'));

        GeneratorUtils::generateTemplate(app_path("/Http/Requests/Store{$model}Request.php"), $storeRequestTemplate);

        GeneratorUtils::generateTemplate(app_path("/Http/Requests/Update{$model}Request.php"), $updateRequestTemplate);
    }
}
