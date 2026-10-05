<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::firstOrCreate(['name' => 'Lectura', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'Escritura', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'Eliminacion', 'guard_name' => 'api']);

        // create roles and assign created permissions
        $roleRegular = Role::firstOrCreate(['name' => 'Usuario Regular', 'guard_name' => 'api'])
            ->givePermissionTo(['Lectura']);

        $roleEditor = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'api'])
            ->givePermissionTo(['Lectura', 'Escritura']);

        $roleAdmin = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'api'])
            ->givePermissionTo(['Lectura', 'Escritura', 'Eliminacion']);

        // create demo users
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole($roleAdmin);

        $editor = User::firstOrCreate(
            ['email' => 'editor@editor.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password123'),
            ]
        );
        $editor->assignRole($roleEditor);
    }
}
