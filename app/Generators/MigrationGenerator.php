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
    public function generate(array $request)
    {
        $model = GeneratorUtils::setModelName($request['model']);
        $tableNamePluralLowercase = GeneratorUtils::pluralSnakeCase($model);

        $setFields = '';
        $totalFields = count($request['fields']);

        foreach ($request['fields'] as $i => $field) {
            /**
             * will generate something like:
             * $table->string('name
             */
            $setFields .= "\$table->" . $request['column_types'][$i] . "('" . str()->snake($field);

            /**
             * will generate something like:
             * $table->string('name
             */
            if ($request['column_types'][$i] == 'enum') {
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
                 * will generate something like:
                 * $table->string('name', ['water', 'fire']
                 */
                $setFields .= "', " . $enum;
            }

            if ($request['max_lengths'][$i] && $request['max_lengths'][$i] >= 0) {
                if ($request['column_types'][$i] == 'enum') {
                    /**
                     * will generate something like:
                     * $table->string('name', ['water', 'fire'])
                     */
                    $setFields .=  ")";
                } else {
                    /**
                     * will generate something like:
                     * $table->string('name', 30)
                     */
                    $setFields .=  "', " . $request['max_lengths'][$i] . ")";
                }
            } else {
                if ($request['column_types'][$i] == 'enum') {
                    /**
                     * will generate something like:
                     * $table->string('name', ['water', 'fire'])
                     */
                    $setFields .=  ")";
                } else {
                    /**
                     * will generate something like:
                     * $table->string('name')
                     */
                    $setFields .= "')";
                }
            }

            if ($request['requireds'][$i] != 'yes') {
                /**
                 * will generate something like:
                 * $table->string('name', 30)->nullable() or $table->string('name')->nullable()
                 */
                $setFields .= "->nullable()";
            }

            $constrainName = '';
            if ($request['column_types'][$i] == 'foreignId') {
                // remove path or '/' if exists
                $constrainName = GeneratorUtils::setModelName($request['constrains'][$i]);
            }

            if ($i + 1 != $totalFields) {
                if ($request['column_types'][$i] == 'foreignId') {
                    $setFields .= "->constrained('" . GeneratorUtils::pluralSnakeCase($constrainName) . "');\n\t\t\t";
                } else {
                    $setFields .= ";\n\t\t\t";
                }
            } else {
                if ($request['column_types'][$i] == 'foreignId') {
                    $setFields .= "->constrained('" . GeneratorUtils::pluralSnakeCase($constrainName) . "');";
                } else {
                    $setFields .= ";";
                }
            }
        }

        $template = str_replace(
            [
                '{{tableNamePluralLowecase}}',
                '{{fields}}'
            ],
            [
                $tableNamePluralLowercase,
                $setFields
            ],
            GeneratorUtils::getTemplate('migration')
        );

        $migrationName = date('Y') . '_' . date('m') . '_' . date('d')  . '_' . date('h') .  date('i') . date('s') . '_create_' . $tableNamePluralLowercase . '_table.php';

        file_put_contents(database_path("/migrations/$migrationName"), $template);
    }
}
