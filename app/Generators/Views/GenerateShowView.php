<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;
use Illuminate\Support\Str;

class GenerateShowView
{
    public function execute(array $request)
    {
        $modelNamePluralUppercase = Str::plural(ucfirst($request['model']), 2);

        $modelNamePluralLowercase = Str::plural(strtolower($request['model']), 2);
        $modelNameSingularLowercase = Str::singular(strtolower($request['model']));

        $trs = "";
        $totalFields = count($request['fields']);

        foreach ($request['fields'] as $i => $field) {
            if ($i >= 1) {
                $trs .= "\t\t\t\t\t\t\t\t\t";
            }

            $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('" . ucwords(str_replace('_', ' ', $field)) . "') }}</td>
                                        <td>{{ $" . $modelNameSingularLowercase . "->" . Str::snake(strtolower($field)) . " }}</td>
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
                $modelNamePluralUppercase,
                $modelNameSingularLowercase,
                $modelNamePluralLowercase,
                $trs
            ],
            GeneratorUtils::getTemplate('views/show')
        );

        GeneratorUtils::checkFolder(resource_path("/views/$modelNamePluralLowercase"));

        GeneratorUtils::generateTemplate(resource_path("/views/$modelNamePluralLowercase/show.blade.php"), $template);
    }
}
