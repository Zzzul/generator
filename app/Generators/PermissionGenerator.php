<?php

namespace App\Generators;

use Spatie\Permission\Models\{Role, Permission};

class PermissionGenerator
{
    /**
     * Generate new permissions to confg.permissions.list_permissions(used for peermissios seeder).
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $model = GeneratorUtils::setModelName($request['model'], 'default');
        $modelNamePlural = GeneratorUtils::cleanPluralLowerCase($model);
        $modelNameSingular = GeneratorUtils::cleanSingularLowerCase($model);

        $stringPermissions = str_replace(
            [
                '{',
                '}',
                ':',
                '"',
                ',',
                ']]'
            ],
            [
                '[',
                ']',
                " => ",
                "'",
                ', ',
                "]], \n\t\t"
            ],
            json_encode([
                'group' => $modelNamePlural,
                'lists' => [
                    "$modelNameSingular view",
                    "$modelNameSingular create",
                    "$modelNameSingular edit",
                    "$modelNameSingular delete",
                ]
            ])
        );

        $path = config_path('permission.php');

        $newPermissionFile = substr(file_get_contents($path), 0, -7) .  $stringPermissions . "],];";

        file_put_contents($path, $newPermissionFile);

        $this->insertRoleAndPermissions($modelNameSingular);
    }

    /**
     * Insert new role & permissions then give an admin that permissions.
     *
     * @param array $request
     * @return void
     */
    protected function insertRoleAndPermissions(string $model)
    {
        $role = Role::findByName('admin');

        Permission::create(['name' => "$model view"]);
        Permission::create(['name' => "$model create"]);
        Permission::create(['name' => "$model edit"]);
        Permission::create(['name' => "$model delete"]);

        $role->givePermissionTo([
            "$model view",
            "$model create",
            "$model edit",
            "$model delete"
        ]);
    }
}
