<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Artisan, File};

class GeneratorController extends Controller
{
    public function create()
    {
        return view('generators.create');
    }

    public function store(Request $request)
    {
        $this->generateModel($request);
        $this->generateMigration($request);
        $this->generateController($request);
        $this->generateRequest($request);
        $this->generateRoute($request);
        $this->generateIndexView($request);
        $this->generateCreateView($request);
        $this->generateEditView($request);
        $this->generateShowView($request);
        $this->generateActionView($request);
        $this->generateFormView($request);
        $this->generateSidebar($request);
        $this->migrateTable();

        return redirect()
            ->route('generators.create')
            ->with('success', trans('Module created successfully.'));
    }

    protected function generateModel($request)
    {
        $name = ucfirst(Str::camel(Str::singular($request->model)));

        $fields = [];

        foreach ($request->fields as $field) {
            $fields[] = Str::snake(strtolower($field));
        }

        $template = str_replace(
            [
                '{{modelName}}',
                '{{fields}}'
            ],
            [
                $name,
                json_encode($fields)
            ],
            $this->getStub('model')
        );

        file_put_contents(app_path("/Models/$name.php"), $template);
    }

    protected function generateMigration($request)
    {
        $namePluralUppercase = Str::plural(ucfirst($request->model), 2);
        $namePluralLowercase = Str::plural(strtolower($request->model), 2);

        $setFields = '';
        $totalFields = count($request->fields);

        foreach ($request->fields as $i => $field) {
            /**
             * will generate like:
             * $table->string('name
             */
            $setFields .= "\$table->" . $request->types[$i] . "('" . Str::snake(strtolower($field));

            if ($request->types[$i] == 'enum') {
                /**
                 * will generate like:
                 * $table->string('name', ['water', 'fire']
                 */
                $setFields .= "', " .  json_encode(explode(';', $request->select_options[$i]));
            }

            if ($request->lengths[$i] && $request->lengths[$i] >= 0) {
                if ($request->types[$i] == 'enum') {
                    /**
                     * will generate like:
                     * $table->string('name', ['water', 'fire'])
                     */
                    $setFields .=  ")";
                } else {
                    /**
                     * will generate like:
                     * $table->string('name', 30)
                     */
                    $setFields .=  "', " . $request->lengths[$i] . ")";
                }
            } else {
                if ($request->types[$i] == 'enum') {
                    /**
                     * will generate like:
                     * $table->string('name', ['water', 'fire'])
                     */
                    $setFields .=  ")";
                } else {
                    /**
                     * will generate like:
                     * $table->string('name')
                     */
                    $setFields .= "')";
                }
            }

            if ($i + 1 != $totalFields) {

                if (isset($request->requireds[$i])) {
                    /**
                     * will generate like:
                     * $table->string('name', 30); or $table->string('name');
                     * with new line and 3x tab
                     */
                    $setFields .= ";\n\t\t\t";
                } else {
                    /**
                     * will generate like:
                     * $table->string('name', 30)->nullable(); or $table->string('name')->nullable();
                     * with new line and 3x tab
                     */
                    $setFields .= "->nullable();\n\t\t\t";
                }
            } else {
                if (isset($request->requireds[$i])) {
                    /**
                     * will generate like:
                     * $table->string('name', 30); or $table->string('name');
                     */
                    $setFields .= ";";
                } else {
                    /**
                     * will generate like:
                     * $table->string('name', 30)->nullable(); or $table->string('name')->nullable();
                     */
                    $setFields .= "->nullable();";
                }
            }
        }

        $template = str_replace(
            [
                '{{tableNamePluralUppercase}}',
                '{{tableNamePluralLowecase}}',
                '{{fields}}'
            ],
            [
                $namePluralUppercase,
                $namePluralLowercase,
                $setFields
            ],
            $this->getStub('migration')
        );

        $migrationName = date('Y') . '_' . date('m') . '_' . date('d')  . '_' . date('h') .  date('i') . date('s') . '_create_' . $namePluralLowercase . '_table';

        file_put_contents(database_path("/migrations/$migrationName.php"), $template);
    }

    protected function generateController($request)
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

    protected function generateRequest($request)
    {
        $name = Str::singular(ucfirst($request->model));

        $validations = '';
        $totalFields = count($request->fields);

        foreach ($request->fields as $i => $field) {
            /**
             * will generate like:
             * 'name' =>
             */
            $validations .= "'" . Str::snake(strtolower($field)) . "' => ";

            /**
             * will generate like:
             * 'name' => 'required
             */
            if (isset($request->requireds[$i])) {
                $validations .= "'required";
            } else {
                $validations .= "'nullable";
            }

            if ($request->types[$i] == 'enum') {
                /**
                 * will generate like:
                 * 'name' => 'required|in:water,fire',
                 */
                $in = "|in:";

                $options = explode(';', $request->select_options[$i]);

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

    protected function generateRoute($request)
    {
        $nameSingularUppercase = Str::singular(ucfirst($request->model));
        $namePluralLowecase = strtolower(Str::plural($nameSingularUppercase, 2));

        $controllerName = "\n\n" . 'Route::resource(\'' . $namePluralLowecase . "', App\Http\Controllers\\" . $nameSingularUppercase . "Controller::class)->middleware('auth');";

        File::append(base_path('routes/web.php'), $controllerName);
    }

    protected function generateIndexView($request)
    {
        $namePluralUppercase = Str::plural(ucfirst($request->model), 2);
        $nameSingularUppercase = Str::singular(ucfirst($request->model));

        $namePluralLowercase = Str::plural(strtolower($request->model), 2);
        $nameSingularLowercase = Str::singular(strtolower($request->model));

        $thColums = '';
        $tdColumns = '';
        $totalFields = count($request->fields);

        foreach ($request->fields as $i => $field) {
            /**
             * will generate like:
             * <th>{{ __('Price') }}</th>
             */
            $thColums .= "<th>{{ __('" .  ucwords(str_replace('_', ' ', $field)) . "') }}</th>";

            /**
             * will generate like:
             * {
                    data: 'price',
                    name: 'price'
                }
             */

            $tdColumns .= "{
                    data: '" . Str::snake(strtolower($field)) . "',
                    name: '" . Str::snake(strtolower($field)) . "'
                },";

            if ($i + 1 != $totalFields) {
                // add new line and tab
                $thColums .= "\n\t\t\t\t\t\t\t\t\t\t\t";
                $tdColumns .= "\n\t\t\t\t";
            }
        }

        $template = str_replace(
            [
                '{{modelNamePluralUppercase}}',
                '{{modelNamePluralLowercase}}',
                '{{modelNameSingularLowercase}}',
                '{{modelNameSingularUppercase}}',
                '{{thColumns}}',
                '{{tdColumns}}'
            ],
            [
                $namePluralUppercase,
                $namePluralLowercase,
                $nameSingularLowercase,
                $nameSingularUppercase,
                $thColums,
                $tdColumns
            ],
            $this->getStub('views/index')
        );

        // make folder
        if (!file_exists($path = resource_path("/views/$namePluralLowercase"))) {
            mkdir($path, 0777, true);
        }

        file_put_contents(resource_path("/views/$namePluralLowercase/index.blade.php"), $template);
    }

    protected function generateActionView($request)
    {
        $namePluralLowercase = Str::plural(strtolower($request->model), 2);
        $nameSingularLowercase = Str::singular(strtolower($request->model));

        $template = str_replace(
            [
                '{{modelNameSingularLowercase}}',
                '{{modelNamePluralLowercase}}'
            ],
            [
                $nameSingularLowercase,
                $namePluralLowercase
            ],
            $this->getStub('views/action')
        );

        // make folder
        if (!file_exists($path = resource_path("/views/$namePluralLowercase/include"))) {
            mkdir($path, 0777, true);
        }

        file_put_contents(resource_path("/views/$namePluralLowercase/include/action.blade.php"), $template);
    }

    protected function generateCreateView($request)
    {
        $namePluralUppercase = Str::plural(ucfirst($request->model), 2);

        $namePluralLowercase = Str::plural(strtolower($request->model), 2);
        $nameSingularLowercase = Str::singular(strtolower($request->model));

        $template = str_replace(
            [
                '{{modelNamePluralUppercase}}',
                '{{modelNameSingularLowercase}}',
                '{{modelNamePluralLowercase}}'
            ],
            [
                $namePluralUppercase,
                $nameSingularLowercase,
                $namePluralLowercase
            ],
            $this->getStub('views/create')
        );

        // make folder
        if (!file_exists($path = resource_path("/views/$namePluralLowercase"))) {
            mkdir($path, 0777, true);
        }

        file_put_contents(resource_path("/views/$namePluralLowercase/create.blade.php"), $template);
    }

    protected function generateEditView($request)
    {
        $namePluralUppercase = Str::plural(ucfirst($request->model), 2);

        $namePluralLowercase = Str::plural(strtolower($request->model), 2);
        $nameSingularLowercase = Str::singular(strtolower($request->model));

        $template = str_replace(
            [
                '{{modelNamePluralUppercase}}',
                '{{modelNameSingularLowercase}}',
                '{{modelNamePluralLowercase}}'
            ],
            [
                $namePluralUppercase,
                $nameSingularLowercase,
                $namePluralLowercase
            ],
            $this->getStub('views/edit')
        );

        // make folder
        if (!file_exists($path = resource_path("/views/$namePluralLowercase"))) {
            mkdir($path, 0777, true);
        }

        file_put_contents(resource_path("/views/$namePluralLowercase/edit.blade.php"), $template);
    }

    protected function generateShowView($request)
    {
        $namePluralUppercase = Str::plural(ucfirst($request->model), 2);

        $namePluralLowercase = Str::plural(strtolower($request->model), 2);
        $nameSingularLowercase = Str::singular(strtolower($request->model));

        $trs = "";
        $totalFields = count($request->fields);

        foreach ($request->fields as $i => $field) {
            if ($i >= 1) {
                $trs .= "\t\t\t\t\t\t\t\t\t";
            }

            $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('" . ucwords(str_replace('_', ' ', $field)) . "') }}</td>
                                        <td>{{ $" . $nameSingularLowercase . "->" . Str::snake(strtolower($field)) . " }}</td>
                                    </tr>";

            if ($i + 1 != $totalFields) {
                $trs .= "\n";
            }
        }

        $template = str_replace(
            [
                '{{modelNamePluralUppercase}}',
                '{{modelNameSingularLowercase}}',
                '{{modelNamePluralLowercase}}',
                '{{trs}}'
            ],
            [
                $namePluralUppercase,
                $nameSingularLowercase,
                $namePluralLowercase,
                $trs
            ],
            $this->getStub('views/show')
        );

        // dd($template);

        // make folder
        if (!file_exists($path = resource_path("/views/$namePluralLowercase"))) {
            mkdir($path, 0777, true);
        }

        file_put_contents(resource_path("/views/$namePluralLowercase/show.blade.php"), $template);
    }

    protected function generateFormView($request)
    {
        $nameSingularLowercase = Str::singular(strtolower($request->model));
        $namePluralLowercase = Str::plural(strtolower($request->model), 2);

        $template = '<div class="row mb-2">';

        foreach ($request->fields as $i => $field) {

            if ($request->types[$i] == 'enum') {
                $lists = "";

                $options = explode(';', $request->select_options[$i]);

                $totalOptions = count($options);

                foreach ($options as $key => $value) {
                    if ($key + 1 != $totalOptions) {
                        $lists .= "<option value=\"$value\">$value</option>\n\t\t\t\t";
                    } else {
                        $lists .= "<option value=\"$value\">$value</option>\t\t\t\t";
                    }
                }

                // select
                $template .= str_replace(
                    [
                        '{{fieldLowercase}}',
                        '{{fieldUppercase}}',
                        '{{options}}',
                        '{{nullable}}'
                    ],
                    [
                        Str::snake(strtolower($field)),
                        ucwords(str_replace('_', ' ', $field)),
                        $lists,
                        isset($request->requireds[$i]) ? ' required' : '',
                    ],
                    $this->getStub('views/forms/select')
                );
            } else if (Str::contains($request->types[$i], 'text')) {

                // textarea
                $template .= str_replace(
                    [
                        '{{fieldLowercase}}',
                        '{{fieldUppercase}}',
                        '{{modelName}}',
                        '{{nullable}}'
                    ],
                    [
                        Str::snake(strtolower($field)),
                        ucwords($field),
                        $nameSingularLowercase,
                        isset($request->requireds[$i]) ? ' required' : '',
                    ],
                    $this->getStub('views/forms/textarea')
                );
            } else {

                // input
                $template .= str_replace(
                    [
                        '{{fieldLowercase}}',
                        '{{fieldUppercase}}',
                        '{{modelName}}',
                        '{{type}}',
                        '{{nullable}}'
                    ],
                    [
                        Str::snake(strtolower($field)),
                        ucwords(Str::replace('_', ' ', $field)),
                        $nameSingularLowercase,
                        $this->setInputType($request->types[$i]),
                        isset($request->requireds[$i]) ? ' required' : '',
                    ],
                    $this->getStub('views/forms/input')
                );
            }
        }

        $template .= "</div>";

        // make folder
        if (!file_exists($path = resource_path("/views/$namePluralLowercase/include"))) {
            mkdir($path, 0777, true);
        }

        file_put_contents(resource_path("/views/$namePluralLowercase/include/form.blade.php"), $template);
    }

    protected function generateSidebar($request)
    {
        $namePluralUppercase = Str::plural(ucfirst($request->model), 2);

        $namePluralLowercase = Str::plural(strtolower($request->model), 2);
        $nameSingularLowercase = Str::singular(strtolower($request->model));

        $sidebarPath = resource_path("/views/layouts/sidebar.blade.php");

        $sidebarTemplade = '{{-- sidebarTemplate --}}' . "\n" . '
                {{-- @can(\'view ' . $nameSingularLowercase . '\') --}}
                <li class="sidebar-item{{ request()->is(\'' . $namePluralLowercase . '*\') ? \' active\' : \'\' }}">
                    <a href="{{ route(\'' . $namePluralLowercase . '.index\') }}" class="sidebar-link">
                        <i class="bi bi-patch-question"></i>
                        <span>{{ __(\'' . $namePluralUppercase . '\') }}</span>
                    </a>
                </li>
                {{-- @endcan --}}';

        $template = str_replace(
            ['{{-- sidebarTemplate --}}'],
            [$sidebarTemplade],
            file_get_contents($sidebarPath)
        );

        file_put_contents($sidebarPath, $template);
    }

    protected function migrateTable()
    {
        Artisan::call('migrate');
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("/stubs/$type.stub"));
    }

    protected function setInputType($type)
    {
        if (
            Str::contains($type, 'integer') ||
            $type == 'float' ||
            $type == 'double' ||
            $type == 'decimal' ||
            $type == 'boolean'
        ) {
            return 'number';
        } elseif ($type == 'date') {
            return 'date';
        } elseif ($type == 'email') {
            return 'email';
        } elseif ($type == 'time') {
            return 'time';
        } elseif ($type == 'datetime') {
            return 'datetime-local';
        } else {
            return 'text';
        }
    }
}
