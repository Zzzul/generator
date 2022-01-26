<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;

class GenerateSidebarView
{
    /**
     * Generate a sidebar menu
     * @param array $request
     * @return void
     */
    public function execute(array $request)
    {
        $modelNamePluralUcWords = GeneratorUtils::cleanPluralUcWords($request['model']);

        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($request['model']);
        $modelNameSingularLowercase = GeneratorUtils::cleanSingularLowerCase($request['model']);

        $sidebarPath = resource_path("/views/layouts/sidebar.blade.php");

        $sidebarTemplade = '{{-- sidebarTemplate --}}' . "\n" . '
                @can(\'view ' . $modelNameSingularLowercase . '\')
                <li class="sidebar-item{{ request()->is(\'' . $modelNamePluralKebabCase . '*\') ? \' active\' : \'\' }}">
                    <a href="{{ route(\'' . $modelNamePluralKebabCase . '.index\') }}" class="sidebar-link">
                        <i class="bi bi-patch-question"></i>
                        <span>{{ __(\'' . $modelNamePluralUcWords . '\') }}</span>
                    </a>
                </li>
                @endcan';

        $template = str_replace(
            ['{{-- sidebarTemplate --}}'],
            [$sidebarTemplade],
            file_get_contents($sidebarPath)
        );

        GeneratorUtils::generateTemplate($sidebarPath, $template);
    }
}
