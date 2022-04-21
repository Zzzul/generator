<?php

namespace App\Http\Controllers;

use App\Enums\GeneratorType;
use App\Services\GeneratorService;
use App\Http\Requests\StoreGeneratorRequest;
use Symfony\Component\HttpFoundation\Response;

class GeneratorController extends Controller
{
    /**
     * @var $generatorService
     */
    public $generatorService;

    public function __construct(GeneratorService $generatorService)
    {
        $this->generatorService = $generatorService;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGeneratorRequest $request)
    {
        if ($request->generate_type == GeneratorType::ALL->value) {
            $this->generatorService->generateAll($request->validated());
        } else {
            $this->generatorService->onlyGenerateModelAndMigration($request->validated());
        }

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

    /**
     * Get all sidebar menus on config by index.
     *
     * @param int $index
     * @return \Illuminate\Http\Response
     */
    public function getSidebarMenus(int $index)
    {
        return $this->generatorService->getSidebarMenusByIndex($index);
    }
}
