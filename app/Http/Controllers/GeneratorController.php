<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class GeneratorController extends Controller
{
    public function create()
    {
        return view('generators.create');
    }

    public function store(Request $request)
    {
        $this->createModel($request);
        $this->createMigration($request);
        $this->createController($request);
        $this->createRequest($request);
        $this->createRoute($request);

        dd('berhasil');
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("/stubs/$type.stub"));
    }

    protected function createModel($request)
    {
        $name = Str::singular(ucfirst($request->model));

        $template = str_replace(
            [
                '{{modelName}}',
                '{{fields}}'
            ],
            [
                $name,
                json_encode($request->fields)
            ],
            $this->getStub('model')
        );

        file_put_contents(app_path("/Models/$name.php"), $template);
    }

    protected function createMigration($request)
    {
        $nameSingularUppercase = Str::singular(ucfirst($request->model));
        $namPpluralLowercase = Str::plural(strtolower($request->model), 2);

        $setFields = '';
        $totalFields = count($request->fields);

        foreach ($request->fields as $i => $field) {
            /**
             * will generate like:
             * $table->string('name
             */
            $setFields .= "\$table->" . $request->types[$i] . "('" . $field;

            if ($request->lengths[$i] && $request->lengths[$i] >= 0) {
                /**
                 * will generate like:
                 * $table->string('name', 30)
                 */
                $setFields .=  "', " . $request->lengths[$i] . ")";
            } else {
                /**
                 * will generate like:
                 * $table->string('name')
                 */
                $setFields .= "')";
            }

            if ($i + 1 != $totalFields) {

                if ($request->nullables[$i] == 'yes') {
                    /**
                     * will generate like:
                     * $table->string('name', 30)->nullable(); or $table->string('name')->nullable();
                     * with new line and 3x tab
                     */
                    $setFields .= "->nullable();\n\t\t\t";
                } else {
                    /**
                     * will generate like:
                     * $table->string('name', 30); or $table->string('name');
                     * with new line and 3x tab
                     */
                    $setFields .= ";\n\t\t\t";
                }
            } else {
                if ($request->nullables[$i] == 'yes') {
                    /**
                     * will generate like:
                     * $table->string('name', 30)->nullable(); or $table->string('name')->nullable();
                     */
                    $setFields .= "->nullable();";
                } else {
                    /**
                     * will generate like:
                     * $table->string('name', 30); or $table->string('name');
                     */
                    $setFields .= ";";
                }
            }
        }

        $template = str_replace(
            [
                '{{tableNameSingularUppercase}}',
                '{{tableNamePluralLowecase}}',
                '{{fields}}'
            ],
            [
                $nameSingularUppercase,
                $namPpluralLowercase,
                $setFields
            ],
            $this->getStub('migration')
        );

        $migrationName = date('Y') . '_' . date('m') . '_' . date('d')  . '_' . date('h') .  date('i') . date('s') . '_create_' . $namPpluralLowercase . '_tables';

        file_put_contents(database_path("/migrations/$migrationName.php"), $template);
    }

    protected function createController($request)
    {
        $nameUppercase = Str::singular(ucfirst($request->model));
        $nameLowercase = Str::singular(strtolower($request->model));
        $pluralLowerCase = Str::plural(strtolower($request->model), 2);

        $template = str_replace(
            [
                '{{modelNameUppercase}}',
                '{{modelNameLowercase}}',
                '{{modelNamePluralLowercase}}'
            ],
            [
                $nameUppercase,
                $nameLowercase,
                $pluralLowerCase
            ],
            $this->getStub('controller')
        );

        file_put_contents(app_path("/Http/Controllers/{$nameUppercase}Controller.php"), $template);
    }

    protected function createRequest($request)
    {
        $name = Str::singular(ucfirst($request->model));

        $validations = '';
        $totalFields = count($request->fields);

        foreach ($request->fields as $i => $field) {
            /**
             * will generate like:
             * 'name' =>
             */
            $validations .= "'" . $field . "' => ";

            /**
             * will generate like:
             * 'name' => 'required
             */
            if ($request->nullables[$i] == 'yes') {
                $validations .= "'nullable";
            } else {
                $validations .= "'required";
            }

            if ($i + 1 != $totalFields) {

                if ($request->lengths[$i] && $request->lengths[$i] >= 0) {
                    /**
                     * will generate like:
                     * 'name' => 'required|max:30',
                     * with new line and 3x tab
                     */
                    $validations .= "|max:" . $request->lengths[$i] . "',\n\t\t\t";
                } else {
                    /**
                     * will generate like:
                     * 'name' => 'required',
                     * with new line and 3x tab
                     */
                    $validations .= "',\n\t\t\t";
                }
            } else {
                if ($request->lengths[$i] && $request->lengths[$i] >= 0) {
                    /**
                     * will generate like:
                     * 'name' => 'required|max:30',
                     */
                    $validations .= "|max:" . $request->lengths[$i] . "',";
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
                '{{modelNameUppercase}}',
                '{{fields}}'
            ],
            [
                "Store$name",
                $validations
            ],
            $this->getStub('request')
        );

        $updateRequestTemplate = str_replace(
            [
                '{{modelNameUppercase}}',
                '{{fields}}'
            ],
            [
                "Update$name",
                $validations
            ],
            $this->getStub('request')
        );

        // make folder
        if (!file_exists($path = app_path('/Http/Requests'))) {
            mkdir($path, 0777, true);
        }

        file_put_contents(app_path("/Http/Requests/Store{$name}Request.php"), $storeRequestTemplate);

        file_put_contents(app_path("/Http/Requests/Update{$name}Request.php"), $updateRequestTemplate);
    }

    protected function createRoute($request)
    {
        $nameSingularUppercase = Str::singular(ucfirst($request->model));
        $namePluralLowecase = strtolower(Str::plural($nameSingularUppercase, 2));

        $controllerName = "\n\n" . 'Route::resource(\'' . $namePluralLowecase . "', App\Http\Controllers\\" . $nameSingularUppercase . "Controller::class)->middleware('auth');";

        File::append(base_path('routes/web.php'), $controllerName);
    }
}
