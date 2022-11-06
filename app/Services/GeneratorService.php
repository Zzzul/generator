<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;
use App\Generators\{
    ControllerGenerator,
    MenuGenerator,
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
};

class GeneratorService
{
    /**
     * Generate all CRUD modules.
     *
     * @param array $request
     * @return void
     */
    public function generateAll(array $request): void
    {
        (new ModelGenerator)->generate($request);
        (new MigrationGenerator)->generate($request);
        (new ControllerGenerator)->generate($request);
        (new RequestGenerator)->generate($request);

        (new IndexViewGenerator)->generate($request);
        (new CreateViewGenerator)->generate($request);
        (new ShowViewGenerator)->generate($request);
        (new EditViewGenerator)->generate($request);
        (new ActionViewGenerator)->generate($request);
        (new FormViewGenerator)->generate($request);

        (new MenuGenerator)->generate($request);
        (new RouteGenerator)->generate($request);
        (new PermissionGenerator)->generate($request);

        if (in_array('foreignId', $request['column_types'])) {
            (new ViewComposerGenerator)->generate($request);
        }

        Artisan::call('migrate');

        $this->checkSidebarType();
    }

    /**
     * Generate only model and migration.
     *
     * @param array $request
     * @return void
     */
    public function onlyGenerateModelAndMigration(array $request): void
    {
        (new ModelGenerator)->generate($request);

        (new MigrationGenerator)->generate($request);
    }

    /**
     * Get sidebar menus by index.
     *
     * @param int $index
     * @return array
     */
    public function getSidebarMenusByIndex(int $index): array
    {
        abort_if(!request()->ajax(), Response::HTTP_FORBIDDEN);

        return config('generator.sidebars')[$index];
    }

    /**
     * Check sidebar view.
     *
     * @return void
     */
    public function checkSidebarType(): void
    {
        $sidebar = file_get_contents(resource_path('views/layouts/sidebar.blade.php'));

        /** if the sidebar is static, then must be regenerated to update new menus */
        if (!str($sidebar)->contains("\$permissions = empty(\$menu['permission'])")) {
            Artisan::call('generator:sidebar dynamic');
        };
    }
}
