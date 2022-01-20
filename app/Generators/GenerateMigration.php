<?php

namespace App\Generators;

use Illuminate\Support\Str;

class GenerateMigration
{
    public function execute(array $request)
    {
        $tableNamePluralUppercase = Str::plural(ucfirst($request['model']), 2);
        $tableNamePluralLowercase = Str::plural(strtolower($request['model']), 2);

        $setFields = '';
        $totalFields = count($request['fields']);

        foreach ($request['fields'] as $i => $field) {
            /**
             * will generate like:
             * $table->string('name
             */
            $setFields .= "\$table->" . $request['types'][$i] . "('" . Str::snake(strtolower($field));

            if ($request['types'][$i] == 'enum') {
                /**
                 * will generate like:
                 * $table->string('name', ['water', 'fire']
                 */
                $setFields .= "', " .  json_encode(explode(';', $request['select_options'][$i]));
            }

            if ($request['lengths'][$i] && $request['lengths'][$i] >= 0) {
                if ($request['types'][$i] == 'enum') {
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
                    $setFields .=  "', " . $request['lengths'][$i] . ")";
                }
            } else {
                if ($request['types'][$i] == 'enum') {
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

                if (isset($request['requireds'][$i])) {
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
                if (isset($request['requireds'][$i])) {
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
                $tableNamePluralUppercase,
                $tableNamePluralLowercase,
                $setFields
            ],
            GeneratorUtils::getTemplate('migration')
        );

        $migrationName = date('Y') . '_' . date('m') . '_' . date('d')  . '_' . date('h') .  date('i') . date('s') . '_create_' . $tableNamePluralLowercase . '_table.php';

        GeneratorUtils::generateTemplate(database_path("/migrations/$migrationName.php"), $template);
    }
}
