<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Cancella cache dei permessi
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Esempio: Crea permessi base
        Permission::firstOrCreate(['name' => 'edit articles']);
        Permission::firstOrCreate(['name' => 'delete articles']);
        Permission::firstOrCreate(['name' => 'publish articles']);

        // Crea ruoli
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Assegna permessi ai ruoli
        $admin->givePermissionTo(Permission::all());
        $user->givePermissionTo('edit articles');
    }
}
