<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Generators\{
    GenerateController,
    GenerateModel,
    GenerateMigration,
    GeneratePermission,
    GenerateRequest,
    GenerateRoute,
    GenerateViewComposer
};
use App\Generators\Views\{
    GenerateActionView,
    GenerateCreateView,
    GenerateEditView,
    GenerateFormView,
    GenerateIndexView,
    GenerateShowView,
    GenerateSidebarView
};

class GeneratorController extends Controller
{
    /**
     * @var GenerateModel $generateModel
     */
    protected $generateModel;

    /**
     * @var GenerateMigration $generateMigration
     */
    protected $generateMigration;

    /**
     * @var GenerateController $generateController
     */
    protected $generateController;

    /**
     * @var GenerateRequest $generateRequest
     */
    protected $generateRequest;

    /**
     * @var GenerateRoute $generateRoute
     */
    protected $generateRoute;

    /**
     * @var GenerateIndexView $generateIndexView
     */
    protected $generateIndexView;

    /**
     * @var GenerateCreateView $generateCreateView
     */
    protected $generateCreateView;

    /**
     * @var GenerateShowView $generateShowView
     */
    protected $generateShowView;

    /**
     * @var GenerateEditView $generateEditView
     */
    protected $generateEditView;

    /**
     * @var GenerateActionView $generateActionView
     */
    protected $generateActionView;

    /**
     * @var GenerateFormView $generateFormView
     */
    protected $generateFormView;

    /**
     * @var GenerateSidebarView $generateSidebarView
     */
    protected $generateSidebarView;

    /**
     * @var GeneratePermission $generatePermission
     */
    protected $generatePermission;

    /**
     * @var GenerateViewComposer $generateViewComposer
     */
    protected $generateViewComposer;

    public function __construct(
        GenerateModel $generateModel,
        GenerateMigration $generateMigration,
        GenerateController $generateController,
        GenerateRequest $generateRequest,
        GenerateRoute $generateRoute,
        GenerateIndexView $generateIndexView,
        GenerateCreateView $generateCreateView,
        GenerateShowView $generateShowView,
        GenerateEditView $generateEditView,
        GenerateActionView $generateActionView,
        GenerateFormView $generateFormView,
        GenerateSidebarView $generateSidebarView,
        GeneratePermission $generatePermission,
        GenerateViewComposer $generateViewComposer,
    ) {
        $this->generateModel = $generateModel;
        $this->generateMigration = $generateMigration;
        $this->generateController = $generateController;
        $this->generateRequest = $generateRequest;
        $this->generateRoute = $generateRoute;
        $this->generateIndexView = $generateIndexView;
        $this->generateCreateView = $generateCreateView;
        $this->generateShowView = $generateShowView;
        $this->generateEditView = $generateEditView;
        $this->generateActionView = $generateActionView;
        $this->generateFormView = $generateFormView;
        $this->generateSidebarView = $generateSidebarView;
        $this->generatePermission = $generatePermission;
        $this->generateViewComposer = $generateViewComposer;
    }

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
    public function store(Request $request)
    {
        if ($request->generate_type == 'all') {
            $this->generateAll($request->all());
        } else {
            $this->generateModel->execute($request->all());
            $this->generateMigration->execute($request->all());
        }

        return redirect()
            ->route('generators.create')
            ->with('success', __('Module created successfully.'));
    }

    /**
     * Generate all modules.
     *
     * @param array $request
     * @return void
     */
    protected function generateAll(array $request)
    {
        $this->generateModel->execute($request);
        $this->generateMigration->execute($request);
        $this->generateController->execute($request);
        $this->generateRequest->execute($request);

        $this->generateIndexView->execute($request);
        $this->generateCreateView->execute($request);
        $this->generateShowView->execute($request);
        $this->generateEditView->execute($request);
        $this->generateActionView->execute($request);
        $this->generateFormView->execute($request);

        $this->generateRoute->execute($request);
        $this->generateSidebarView->execute($request);
        $this->generatePermission->execute($request);

        if (in_array('foreignId', $request['data_types'])) {
            $this->generateViewComposer->execute($request);
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
}
