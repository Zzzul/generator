<?php

namespace App\Generators;

use Spatie\Permission\Models\{Role, Permission};

class GeneratePermission
{
    /**
     * Generate new permissions to confg.permissions.list_permissions(used for peermissios seeder)
     * @param array $request
     * @return void
     */
    public function execute(array $request)
    {
        $modelNamePlural = GeneratorUtils::cleanPluralLowerCase($request['model']);
        $modelNameSingular = GeneratorUtils::cleanSingularLowerCase($request['model']);

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
                "]], \n\t\t// Don't remove comment below, it will generate new permissions"
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
                "// Don't remove comment below, it will generate new permissions"
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
     * Give role admin new permissions
     * @param array $request
     * @return void
     */
    protected function setRoleAndPermissions($model)
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
