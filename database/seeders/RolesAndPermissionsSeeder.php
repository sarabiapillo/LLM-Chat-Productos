<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Client;
use App\Models\Company;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permisos para api y web
        foreach (['web', 'api'] as $guard) {
            Permission::firstOrCreate(['name' => 'Lectura', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'Escritura', 'guard_name' => $guard]);
            Permission::firstOrCreate(['name' => 'Eliminacion', 'guard_name' => $guard]);

            // Rol 1: Administrador
            Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => $guard])
                ->givePermissionTo(['Lectura', 'Escritura', 'Eliminacion']);

            // Rol 2: Empresa
            Role::firstOrCreate(['name' => 'Empresa', 'guard_name' => $guard])
                ->givePermissionTo(['Lectura', 'Escritura']);

            // Rol 3: Cliente
            Role::firstOrCreate(['name' => 'Cliente', 'guard_name' => $guard])
                ->givePermissionTo(['Lectura']);
        }

        // 1. Crear Empresa Demo 1 (Tech Corp)
        $company1 = Company::firstOrCreate(
            ['slug' => 'tech-corp'],
            [
                'name' => 'Tech Corp Inc.',
                'email' => 'empresa@techcorp.com',
                'phone' => '+52 961 123 4567',
                'plan' => 'Premium',
                'status' => 'Activo',
            ]
        );

        // 2. Crear Empresa Demo 2 (InnovaTech)
        $company2 = Company::firstOrCreate(
            ['slug' => 'innovatech'],
            [
                'name' => 'InnovaTech Solutions',
                'email' => 'contacto@innovatech.io',
                'phone' => '+52 961 987 6543',
                'plan' => 'Plus',
                'status' => 'Activo',
            ]
        );

        // 3. Crear Categorías y Productos para Empresa 1 (Tech Corp)
        $catSoft = Category::firstOrCreate(
            ['company_id' => $company1->id, 'name' => 'Software & Licencias'],
            ['description' => 'Módulos y licencias de software SaaS']
        );

        $catServ = Category::firstOrCreate(
            ['company_id' => $company1->id, 'name' => 'Servicios LLM'],
            ['description' => 'Integración y consultoría de IA']
        );

        Product::firstOrCreate(
            ['company_id' => $company1->id, 'name' => 'Licencia Anual LLM Chat Pro'],
            ['category_id' => $catSoft->id, 'description' => 'Acceso multiusuario con modelos avanzados GPT-4o y Gemini Pro.', 'price' => 4999.00, 'is_active' => true]
        );

        Product::firstOrCreate(
            ['company_id' => $company1->id, 'name' => 'API Token Enterprise 10M Tokens'],
            ['category_id' => $catServ->id, 'description' => 'Bolsa de tokens para consumo vía API Kotlin Android.', 'price' => 1250.50, 'is_active' => true]
        );

        // 4. Crear Categorías y Productos para Empresa 2 (InnovaTech)
        $catHardware = Category::firstOrCreate(
            ['company_id' => $company2->id, 'name' => 'Hardware IoT & QR'],
            ['description' => 'Escáneres y dispositivos de lectura QR']
        );

        Product::firstOrCreate(
            ['company_id' => $company2->id, 'name' => 'Escáner QR Bluetooth Android'],
            ['category_id' => $catHardware->id, 'description' => 'Lector QR inalámbrico compatible con App Kotlin Android.', 'price' => 899.00, 'is_active' => true]
        );

        // 5. Usuarios Demo
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrador Principal',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );
        $admin->syncRoles(['Administrador']);

        $empresaUser = User::firstOrCreate(
            ['email' => 'empresa@techcorp.com'],
            [
                'name' => 'Tech Corp Empresa',
                'password' => Hash::make('password123'),
                'role' => 'empresa',
                'company_id' => $company1->id,
            ]
        );
        $empresaUser->syncRoles(['Empresa']);

        $clienteUser = User::firstOrCreate(
            ['email' => 'cliente@ejemplo.com'],
            [
                'name' => 'Carlos Cliente',
                'password' => Hash::make('password123'),
                'role' => 'cliente',
            ]
        );
        $clienteUser->syncRoles(['Cliente']);

        // Clientes de prueba
        Client::firstOrCreate(
            ['email' => 'contacto@techcorp.com'],
            [
                'name' => 'Tech Corp Inc.',
                'phone' => '+52 961 123 4567',
                'company' => 'Tech Corp',
                'status' => 'Activo',
                'notes' => 'Empresa cliente registrada en el sistema Multi-tenant.'
            ]
        );
    }
}
