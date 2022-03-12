<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGeneratorRequest;
use Illuminate\Support\Facades\Artisan;
use App\Generators\{
    ControllerGenerator,
    ModelGenerator,
    MigrationGenerator,
    PermissionGenerator,
    RequestGenerator,
    RouteGenerator,
    ViewComposerGenerator
};
use App\Generators\Views\{
    ActionViewGenerator,
    CreateViewGenerator,
    EditViewGenerator,
    FormViewGenerator,
    IndexViewGenerator,
    ShowViewGenerator,
    SidebarViewGenerator
};
use Illuminate\Http\Request;

class GeneratorController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('generators.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGeneratorRequest $request)
    {
        $attr = $request->validated();
        $menu =  json_decode($request->menu, true);

        return config('generator.sidebars')[$menu['sidebar']]['menus'][$menu['menus']]['title'];

        // if ($request->generate_type == 'all') {
        //     $this->generateAll($request->validated());
        // } else {
        //     (new ModelGenerator)->execute($request->validated());

        //     (new MigrationGenerator)->execute($request->validated());
        // }

        // return redirect()
        //     ->route('generators.create')
        //     ->with('success', __('Module created successfully.'));
    }

    /**
     * Generate all modules.
     *
     * @param array $request
     * @return void
     */
    protected function generateAll(array $request)
    {
        (new ModelGenerator)->execute($request);
        (new MigrationGenerator)->execute($request);
        (new ControllerGenerator)->execute($request);
        (new RequestGenerator)->execute($request);

        (new IndexViewGenerator)->execute($request);
        (new CreateViewGenerator)->execute($request);
        (new ShowViewGenerator)->execute($request);
        (new EditViewGenerator)->execute($request);
        (new ActionViewGenerator)->execute($request);
        (new FormViewGenerator)->execute($request);
        (new SidebarViewGenerator)->execute($request);

        (new RouteGenerator)->execute($request);

        (new PermissionGenerator)->execute($request);

        if (in_array('foreignId', $request['data_types'])) {
            (new ViewComposerGenerator)->execute($request);
        }

        $this->clearCache();
        $this->migrateTable();
    }

    protected function migrateTable(): void
    {
        Artisan::call('migrate');
    }

    protected function clearCache(): void
    {
        Artisan::call('optimize:clear');
    }

    public function test()
    {
        $config = config('generator.sidebars');
        $route = config('generator.route');

        // get data types on config(array), convert to json and convert again to string(format like an array)
        $dataTypes = str_replace(
            [
                '",',
                '"',
                "['",
                "']",
                "\t'",
            ],
            [
                "', \n\t",
                "'",
                "[\n\t'",
                "'\n\t]",
                "\t\t'"
            ],
            json_encode(config('generator.data_types'))
        );

        $search = json_encode($config[0]['menus'][2]['route']) . ',"sub_menus":[';

        dump(json_encode($config));
        dump($search);

        // convert json to array
        $replace = json_decode(str_replace(
            $search,
            $search . json_encode([
                'title' => 'Books',
                'route' => '/books'
            ]),
            json_encode($config)
        ), true);

        // convert json to string(format like an array)
        $jsonToArrayText = str_replace(
            [
                '{',
                '}',
                ':',
                '"',
                "','",
                "\\",
                "='",
                "'>"
            ],
            [
                '[',
                ']',
                ' => ',
                "'",
                "', '",
                '',
                '="',
                '">'
            ],
            json_encode($replace, JSON_PRETTY_PRINT)
        );

        $jsonToArrayText = "<?php " . PHP_EOL . "\nreturn [ " . PHP_EOL . "\t'route' => '$route'," . PHP_EOL . "\t'data_types' => $dataTypes," . PHP_EOL . "\t'sidebars' => " . $jsonToArrayText . "\n];";

        file_put_contents(base_path('config/generator-test.php'), $jsonToArrayText);

        dump($jsonToArrayText);
    }
}
