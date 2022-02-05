<?php

namespace App\Generators;

class RequestGenerator
{
    /**
     * Generate a request validation class file
     *
     * @param array $request
     * @return void
     */
    public function execute(array $request)
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
            if ($request['requireds'][$i] == 'yes') {
                $validations .= "'required";
            } else {
                $validations .= "'nullable";
            }

            if ($request['data_types'][$i] == 'enum') {
                /**
                 * will generate like:
                 * 'name' => 'required|in:water,fire',
                 */
                $in = "|in:";

                $options = explode('|', $request['select_options'][$i]);

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

            if ($request['input_types'][$i] == 'email') {
                /**
                 * will generate like:
                 * 'name' => 'required|email',
                 */
                $validations .= "|email";
            }

            if ($request['input_types'][$i] == 'string') {
                /**
                 * will generate like:
                 * 'name' => 'required|string',
                 */
                $validations .= "|string";
            }

            if ($request['input_types'][$i] == 'number') {
                /**
                 * will generate like:
                 * 'name' => 'required|numeric',
                 */
                $validations .= "|numeric";
            }

            if ($request['input_types'][$i] == 'date') {
                /**
                 * will generate like:
                 * 'name' => 'required|date',
                 */
                $validations .= "|date";
            }

            if ($request['input_types'][$i] == 'file' && $request['file_types'][$i] == 'image') {
                /**
                 * will generate like:
                 * 'name' => 'required|image|size:1024',
                 */
                $validations .= "|image|max:" . $request['files_sizes'][$i];
            } elseif ($request['input_types'][$i] == 'file' && $request['file_types'][$i] == 'mimes') {
                /**
                 * will generate like:
                 * 'name' => 'required|mimes|size:1024',
                 */
                $validations .= "|mimes:" . $request['mimes'][$i] . "|size:" . $request['files_sizes'][$i];
            }

            if ($request['min_lengths'][$i] && $request['min_lengths'][$i] >= 0) {
                /**
                 * will generate like:
                 * 'name' => 'required|min:5',
                 */
                $validations .= "|min:" . $request['min_lengths'][$i];
            }

            if ($request['max_lengths'][$i] && $request['max_lengths'][$i] >= 0) {
                /**
                 * will generate like:
                 * 'name' => 'required|max:30',
                 */
                $validations .= "|max:" . $request['max_lengths'][$i];
            }

            if ($i + 1 != $totalFields) {
                if ($request['data_types'][$i] == 'foreignId') {
                    /**
                     * will generate like:
                     * 'name' => 'required|max:30|exists:App\Models\Product,id',
                     * with new line and 3x tab
                     */
                    $validations .= "|exists:App\Models\\" . GeneratorUtils::singularPascalCase($request['constrains'][$i]) . ",id',\n\t\t\t";
                } else {
                    /**
                     * will generate like:
                     * 'name' => 'required|max:30|exists:App\Models\Product,id',
                     * with new line and 3x tab
                     */
                    $validations .= "',\n\t\t\t";
                }
            } else {
                if ($request['data_types'][$i] == 'foreignId') {
                    /**
                     * will generate like:
                     * 'name' => 'required|max:30|exists:App\Models\Product,id',
                     */
                    $validations .= "|exists:App\Models\\" . GeneratorUtils::singularPascalCase($request['constrains'][$i]) . ",id',";
                } else {
                    /**
                     * will generate like:
                     * 'name' => 'required|max:30|exists:App\Models\Product,id',
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

        /**
         * on update request if any image, then set 'required' to nullbale
         */
        if (\Str::contains($validations, "'required|image")) {
            $updateValidations = str_replace("'required|image", "'nullable|image", $validations);
        } else {
            $updateValidations = $validations;
        }

        $updateRequestTemplate = str_replace(
            [
                '{{modelNamePascalCase}}',
                '{{fields}}'
            ],
            [
                "Update$model",
                $updateValidations
            ],
            GeneratorUtils::getTemplate('request')
        );

        GeneratorUtils::checkFolder(app_path('/Http/Requests'));

        GeneratorUtils::generateTemplate(app_path("/Http/Requests/Store{$model}Request.php"), $storeRequestTemplate);

        GeneratorUtils::generateTemplate(app_path("/Http/Requests/Update{$model}Request.php"), $updateRequestTemplate);
    }
}
