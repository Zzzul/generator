<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;
use Illuminate\Support\Str;

class GenerateSidebarView
{
    public function execute(array $request)
    {
        $modelNamePluralUppercase = Str::plural(ucfirst($request['model']), 2);

        $modelNamePluralLowercase = Str::plural(strtolower($request['model']), 2);
        $modelNameSingularLowercase = Str::singular(strtolower($request['model']));

        $sidebarPath = resource_path("/views/layouts/sidebar.blade.php");

        $sidebarTemplade = '{{-- sidebarTemplate --}}' . "\n" . '
                {{-- @can(\'view ' . $modelNameSingularLowercase . '\') --}}
                <li class="sidebar-item{{ request()->is(\'' . $modelNamePluralLowercase . '*\') ? \' active\' : \'\' }}">
                    <a href="{{ route(\'' . $modelNamePluralLowercase . '.index\') }}" class="sidebar-link">
                        <i class="bi bi-patch-question"></i>
                        <span>{{ __(\'' . $modelNamePluralUppercase . '\') }}</span>
                    </a>
                </li>
                {{-- @endcan --}}';

        $template = str_replace(
            ['{{-- sidebarTemplate --}}'],
            [$sidebarTemplade],
            file_get_contents($sidebarPath)
        );

        GeneratorUtils::generateTemplate($sidebarPath, $template);
    }
}
