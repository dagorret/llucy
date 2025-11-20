<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear usuario admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@lucy.test'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Crear rol super_admin
        $role = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web'
        ]);

        // 3. Darle todos los permisos existentes
        $role->givePermissionTo(Permission::all());

        // 4. Asignar rol al usuario admin
        if (! $admin->hasRole('super_admin')) {
            $admin->assignRole($role);
        }

        echo "✔ Usuario admin creado: admin@lucy.test / admin123\n";
    }
}

