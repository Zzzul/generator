<?php

namespace App\Generators;

use function PHPUnit\Framework\matches;

class RequestGenerator
{
    /**
     * Generate a request validation class file.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $model = GeneratorUtils::setModelName($request['model']);
        $path = GeneratorUtils::getModelLocation($request['model']);

        $validations = '';
        $totalFields = count($request['fields']);

        if ($path != '') {
            $namespace = "namespace App\Http\Requests\\$path;";
        } else {
            $namespace = "namespace App\Http\Requests;";
        }

        foreach ($request['fields'] as $i => $field) {
            /**
             * will generate like:
             * 'name' =>
             */
            $validations .= "'" . str()->snake($field) . "' => ";

            /**
             * will generate like:
             * 'name' => 'required
             */
            match ($request['requireds'][$i]) {
                'yes' => $validations .= "'required",
                default => $validations .= "'nullable"
            };

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

            if ($request['column_types'][$i] == 'enum') {
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
                        // for latest validation
                        $in .= $option;
                    }
                }

                $validations .= $in;
            }

            if ($request['input_types'][$i] == 'text' || $request['input_types'][$i] == 'textarea') {
                /**
                 * will generate like:
                 * 'name' => 'required|string',
                 */
                $validations .= "|string";
            }

            if ($request['input_types'][$i] == 'url') {
                /**
                 * will generate like:
                 * 'name' => 'required|url',
                 */
                $validations .= "|url";
            }

            if ($request['column_types'][$i] == 'boolean') {
                /**
                 * will generate like:
                 * 'name' => 'required|boolean',
                 */
                $validations .= "|boolean";
            }

            if ($request['input_types'][$i] == 'number' || $request['column_types'][$i] == 'year' || $request['input_types'][$i] == 'range') {
                /**
                 * will generate like:
                 * 'name' => 'required|numeric',
                 */
                $validations .= "|numeric";
            }

            if ($request['input_types'][$i] == 'range' && $request['max_lengths'][$i] >= 0) {
                /**
                 * will generate like:
                 * 'name' => 'numeric|between:1,10',
                 */
                $validations .= "|between:" . $request['min_lengths'][$i] . "," . $request['max_lengths'][$i];
            }

            if ($request['input_types'][$i] == 'date') {
                /**
                 * will generate like:
                 * 'name' => 'required|date',
                 */
                $validations .= "|date";
            }

            if ($request['min_lengths'][$i] && $request['input_types'][$i] !== 'range') {
                /**
                 * will generate like:
                 * 'name' => 'required|min:5',
                 */
                $validations .= "|min:" . $request['min_lengths'][$i];
            }

            if ($request['max_lengths'][$i] && $request['max_lengths'][$i] >= 0 && $request['input_types'][$i] !== 'range') {
                /**
                 * will generate like:
                 * 'name' => 'required|max:30',
                 */
                $validations .= "|max:" . $request['max_lengths'][$i];
            }

            if ($request['input_types'][$i] == 'email') {
                $uniqueValidation = 'unique:' . GeneratorUtils::pluralSnakeCase($model) . ',' . GeneratorUtils::singularSnakeCase($field);

                /**
                 * will generate like:
                 * 'name' => 'required|email',
                 */
                $validations .= "|email|" . $uniqueValidation;
            }

            if ($request['column_types'][$i] == 'foreignId') {
                // remove '/' or sub folders
                $constrainModel = GeneratorUtils::setModelName($request['constrains'][$i]);
                $constrainpath = GeneratorUtils::getModelLocation($request['constrains'][$i]);

                switch ($constrainpath != '') {
                    case true:
                        /**
                         * will generate like:
                         * 'name' => 'required|max:30|exists:App\Models\Master\Product,id',
                         */
                        $validations .= "|exists:App\Models\\" . str_replace('/', '\\', $constrainpath) . "\\" . GeneratorUtils::singularPascalCase($constrainModel) . ",id',";
                        break;
                    default:
                        /**
                         * will generate like:
                         * 'name' => 'required|max:30|exists:App\Models\Product,id',
                         */
                        $validations .= "|exists:App\Models\\" . GeneratorUtils::singularPascalCase($constrainModel) . ",id',";
                        break;
                }
            } else {
                /**
                 * will generate like:
                 * 'name' => 'required|max:30|exists:App\Models\Product,id',
                 */
                $validations .= "',";
            }

            if ($i + 1 != $totalFields) {
                $validations .= "\n\t\t\t";
            }
        }
        // end of foreach

        $storeRequestTemplate = str_replace(
            [
                '{{modelNamePascalCase}}',
                '{{fields}}',
                '{{namespace}}',
            ],
            [
                "Store$model",
                $validations,
                $namespace
            ],
            GeneratorUtils::getTemplate('request')
        );

        /**
         * on update request if any image validation, then set 'required' to nullbale
         */
        switch (str_contains($storeRequestTemplate, "required|image")) {
            case true:
                $updateValidations = str_replace("required|image", "nullable|image", $validations);
                break;
            default:
                $updateValidations = $validations;
                break;
        }

        if (isset($uniqueValidation)) {
            /**
             * Will generate something like:
             *
             * unique:users,email,' . $this->user->id
             */
            $updateValidations = str_replace($uniqueValidation, $uniqueValidation . ",' . \$this->" . GeneratorUtils::singularCamelCase($model) . "->id", $validations);

            // change ->id', to ->id,
            $updateValidations = str_replace("->id'", "->id", $updateValidations);
        }

        if (in_array('password', $request['input_types'])) {
            foreach ($request['input_types'] as $key => $input) {
                if ($input == 'password' && $request['requireds'][$key] == 'yes') {
                    /**
                     * will generate something like:
                     *
                     * 'password' => 'required' to 'password' => 'nullable'
                     */
                    $updateValidations = str_replace(
                        "'" . $request['fields'][$key] . "' => 'required",
                        "'" . $request['fields'][$key] . "' => 'nullable",
                        $updateValidations
                    );
                }
            }
        }

        $updateRequestTemplate = str_replace(
            [
                '{{modelNamePascalCase}}',
                '{{fields}}',
                '{{namespace}}',
            ],
            [
                "Update$model",
                $updateValidations,
                $namespace
            ],
            GeneratorUtils::getTemplate('request')
        );

        /**
         * Create a request class file.
         */
        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(app_path('/Http/Requests'));

                file_put_contents(app_path("/Http/Requests/Store{$model}Request.php"), $storeRequestTemplate);
                file_put_contents(app_path("/Http/Requests/Update{$model}Request.php"), $updateRequestTemplate);
                break;
            default:
                $fullPath = app_path("/Http/Requests/$path");

                GeneratorUtils::checkFolder($fullPath);

                file_put_contents("$fullPath/Store{$model}Request.php", $storeRequestTemplate);
                file_put_contents("$fullPath/Update{$model}Request.php", $updateRequestTemplate);
                break;
        }
    }
}
