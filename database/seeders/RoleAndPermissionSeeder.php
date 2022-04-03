<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Role, Permission};

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roleAdmin = Role::create(['name' => 'Admin']);

        foreach (config('permission.list_permissions') as $permission) {
            foreach ($permission['lists'] as $list) {
                Permission::create(['name' => $list]);
            }
        }

        $userAdmin = User::first();
        $userAdmin->assignRole('admin');
        $roleAdmin->givePermissionTo(Permission::all());
    }
}
