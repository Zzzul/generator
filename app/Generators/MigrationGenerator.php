<?php

namespace App\Generators;

use App\Enums\ActionForeign;

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
                    if($request['on_delete_foreign'][$i] == ActionForeign::NULL->value){
                        $setFields .= "->nullable()";
                    }

                    $setFields .= "->constrained('" . GeneratorUtils::pluralSnakeCase($constrainName) . "')";

                    if($request['on_update_foreign'][$i] == ActionForeign::CASCADE->value){
                        $setFields .= "->cascadeOnUpdate()";
                    }elseif($request['on_update_foreign'][$i] == ActionForeign::RESTRICT->value){
                        $setFields .= "->restrictOnUpdate()";
                    }

                    if($request['on_delete_foreign'][$i] == ActionForeign::CASCADE->value){
                        $setFields .= "->cascadeOnDelete();\n\t\t\t";
                    }elseif($request['on_delete_foreign'][$i] == ActionForeign::RESTRICT->value){
                        $setFields .= "->restrictOnDelete();\n\t\t\t";
                    }elseif($request['on_delete_foreign'][$i] == ActionForeign::NULL->value){
                        $setFields .= "->nullOnDelete();\n\t\t\t";
                    }else{
                        $setFields .= ";\n\t\t\t";
                    }
                } else {
                    $setFields .= ";\n\t\t\t";
                }
            } else {
                if ($request['column_types'][$i] == 'foreignId') {
                    $setFields .= "->constrained('" . GeneratorUtils::pluralSnakeCase($constrainName) . "')";

                    if($request['on_update_foreign'][$i] == ActionForeign::CASCADE->value){
                        $setFields .= "->cascadeOnUpdate()";
                    }elseif($request['on_update_foreign'][$i] == ActionForeign::RESTRICT->value){
                        $setFields .= "->restrictOnUpdate()";
                    }

                    if($request['on_delete_foreign'][$i] == ActionForeign::CASCADE->value){
                        $setFields .= "->cascadeOnDelete();";
                    }elseif($request['on_delete_foreign'][$i] == ActionForeign::RESTRICT->value){
                        $setFields .= "->restrictOnDelete();";
                    }elseif($request['on_delete_foreign'][$i] == ActionForeign::NULL->value){
                        $setFields .= "->nullOnDelete();";
                    }else{
                        $setFields .= ";";
                    }
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
