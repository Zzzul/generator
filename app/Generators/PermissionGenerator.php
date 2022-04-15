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
        $model = GeneratorUtils::setModelName($request['model']);
        $modelNamePlural = GeneratorUtils::cleanPluralLowerCase($model);
        $modelNameSingular = GeneratorUtils::cleanSingularLowerCase($model);

        $permissions = str_replace(
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
                "]], \n\t\t// Don't remove this comment, it will used as 'search param' to generate a new permission"
            ],
            json_encode([
                'group' => $modelNamePlural,
                'lists' => [
                    "view $modelNameSingular",
                    "create $modelNameSingular",
                    "edit $modelNameSingular",
                    "delete $modelNameSingular",
                ]
            ])
        );

        $path = config_path('permission.php');
        $permissionFile = file_get_contents($path);

        $newPermissionFile = str_replace(
            [
                "// Don't remove this comment, it will used as 'search param' to generate a new permission"
            ],
            [
                $permissions
            ],
            $permissionFile
        );

        file_put_contents($path, $newPermissionFile);

        $this->setRoleAndPermissions($modelNameSingular);
    }

    /**
     * Give role admin new permissions.
     *
     * @param array $request
     * @return void
     */
    protected function setRoleAndPermissions(string $model)
    {
        $role = Role::findByName('admin');

        Permission::create(['name' => "view $model"]);
        Permission::create(['name' => "create $model"]);
        Permission::create(['name' => "edit $model"]);
        Permission::create(['name' => "delete $model"]);

        $role->givePermissionTo([
            "view $model",
            "create $model",
            "edit $model",
            "delete $model"
        ]);
    }
}
