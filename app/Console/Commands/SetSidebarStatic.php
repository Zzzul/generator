<?php

namespace App\Console\Commands;

use App\Generators\GeneratorUtils;
use Illuminate\Console\Command;

class SetSidebarStatic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generator:sidebar {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set a sidebar menu to fully blade code(static) or use a list from config(dynamic)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        switch ($this->argument('type')) {
            case 'static':
                $sidebarCode = "";
                $sidebarCode .= "@canany([";

                foreach (config('generator.sidebars') as $sidebar) :
                    if (isset($sidebar['permissions'])) {
                        $sidebarCode .= GeneratorUtils::convertArraySidebarToString($sidebar['permissions']);
                    }
                endforeach;
                $sidebarCode .= "])\n\t";

                foreach (config('generator.sidebars') as $i => $sidebar) :
                    if (isset($sidebar['permissions'])) {
                        $sidebarCode .= "
                            <li class=\"sidebar-title\">{{ __('" . $sidebar['header'] . "') }}</li>
                            @canany([";

                        foreach ($sidebar['menus'] as $menu) {
                            $permissions = empty($menu['permission']) ? $menu['permissions'] : [$menu['permission']];
                            $sidebarCode .= GeneratorUtils::convertArraySidebarToString($permissions);
                        }

                        $sidebarCode .= "])\n\t";

                        foreach ($sidebar['menus'] as $key => $menu) {
                            if ($menu['submenus'] == []) {
                                $sidebarCode .= "
                                @can('" . $menu['permission'] . "')
                                    <li class=\"sidebar-item{{ App\Generators\GeneratorUtils::isActiveMenu('" . $menu['route'] . "') }}\">
                                    <a href=\"{{ route('". str($menu['route'])->remove('/')->plural() . '.index' ."') }}\" class=\"sidebar-link\">
                                            " . $menu['icon'] . "
                                            <span>{{ __('" . $menu['title'] . "') }}</span>
                                        </a>
                                    </li>
                                @endcan\n";
                            } else {
                                $sidebarCode .= "<li class=\"sidebar-item has-sub{{  App\Generators\GeneratorUtils::isActiveMenu([" . GeneratorUtils::convertArraySidebarToString($permissions) . "]) }}\">
                                <a href=\"#\" class=\"sidebar-link\">
                                    " . $menu['icon'] . "
                                    <span>{{ __('" . $menu['title'] . "') }}</span>
                                </a>
                                <ul class=\"submenu\">
                                @canany([" . GeneratorUtils::convertArraySidebarToString($permissions) . "])";

                                foreach ($menu['submenus'] as $submenu) {
                                    $sidebarCode .= "
                                    @can('" . $submenu['permission'] . "')
                                        <li class=\"submenu-item\">
                                        <a href=\"{{ route('". str($submenu['route'])->remove('/')->plural() . '.index' ."') }}\">{{ __('" . $submenu['title'] . "') }}</a>
                                        </li>
                                    @endcan\n";
                                }

                                $sidebarCode .= "\n\t@endcanany\n</ul>\n\t</li>\n";
                            }
                        }
                        $sidebarCode .= "@endcanany\n";
                    }
                endforeach;

                $sidebarCode .= "\n\t@endcanany";

                $template = str_replace(
                    '{{listSidebars}}',
                    str_replace(
                        "', ]",
                        "']",
                        $sidebarCode
                    ),
                    GeneratorUtils::getTemplate('sidebar-static')
                );

                file_put_contents(resource_path('views/layouts/sidebar.blade.php'), $template);

                $this->info('Now, sidebar menus are fully blade code.');
                break;
            case 'dynamic':
                file_put_contents(resource_path('views/layouts/sidebar.blade.php'), GeneratorUtils::getTemplate('sidebar-dynamic'));

                $this->info('The sidebar used a dynamic list from config.');
                break;
            default:
                $this->error("The type must be 'static' or 'dynamic'!");
                break;
        }
    }
}
