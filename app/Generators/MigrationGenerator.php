<?php

namespace App\Generators;

class MigrationGenerator
{
    /**
     * Generate a migration file.
     *
     * @param array $request
     * @return void
     */
    public function execute(array $request)
    {
        $tableNamePluralUppercase = GeneratorUtils::pluralPascalCase($request['model']);
        $tableNamePluralLowercase = GeneratorUtils::pluralSnakeCase($request['model']);

        $setFields = '';
        $totalFields = count($request['fields']);

        foreach ($request['fields'] as $i => $field) {
            /**
             * will generate like:
             * $table->string('name
             */
            $setFields .= "\$table->" . $request['data_types'][$i] . "('" . GeneratorUtils::singularSnakeCase($field);

            /**
             * will generate like:
             * $table->string('name
             */
            if ($request['data_types'][$i] == 'enum') {
                $options = explode('|', $request['select_options'][$i]);
                $totalOptions = count($options);

                $enum = "[";

                foreach ($options as $key => $value) {
                    if ($key + 1 != $totalOptions) {
                        $enum .= "'$value', ";
                    } else {
                        $enum .= "'$value']";
                    }
                }

                /**
                 * will generate like:
                 * $table->string('name', ['water', 'fire']
                 */
                $setFields .= "', " . $enum;
            }

            if ($request['max_lengths'][$i] && $request['max_lengths'][$i] >= 0) {
                if ($request['data_types'][$i] == 'enum') {
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
                    $setFields .=  "', " . $request['max_lengths'][$i] . ")";
                }
            } else {
                if ($request['data_types'][$i] == 'enum') {
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

            if ($request['requireds'][$i] != 'yes') {
                /**
                 * will generate like:
                 * $table->string('name', 30)->nullable() or $table->string('name')->nullable()
                 */
                $setFields .= "->nullable()";
            }

            if ($i + 1 != $totalFields) {
                if ($request['data_types'][$i] == 'foreignId') {
                    $setFields .= "->constrained('" . GeneratorUtils::pluralSnakeCase($request['constrains'][$i]) . "');\n\t\t\t";
                } else {
                    $setFields .= ";\n\t\t\t";
                }
            } else {
                if ($request['data_types'][$i] == 'foreignId') {
                    $setFields .= "->constrained('" . GeneratorUtils::pluralSnakeCase($request['constrains'][$i]) . "');";
                } else {
                    $setFields .= ";";
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

        GeneratorUtils::generateTemplate(database_path("/migrations/$migrationName"), $template);
    }
}