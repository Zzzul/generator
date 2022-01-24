<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Generators\{
    GenerateController,
    GenerateModel,
    GenerateMigration,
    GenerateRequest,
    GenerateRoute
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
        // return $request;

        // $this->generateModel->execute($request->all());
        // $this->generateMigration->execute($request->all());
        // $this->generateController->execute($request->all());
        $this->generateRequest->execute($request->all());
        // $this->generateRoute->execute($request->all());
        // $this->generateIndexView->execute($request->all());
        // $this->generateCreateView->execute($request->all());
        // $this->generateShowView->execute($request->all());
        // $this->generateEditView->execute($request->all());
        // $this->generateActionView->execute($request->all());
        // $this->generateFormView->execute($request->all());
        // $this->generateSidebarView->execute($request->all());
        // $this->clearCache();
        // $this->migrateTable();

        // return redirect()
        //     ->route('generators.create')
        //     ->with('success', trans('Module created successfully.'));

        return $request;
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
