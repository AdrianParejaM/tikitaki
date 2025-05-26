<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Rol de administrador
        Role::create(['name' => 'Admin']);

        //Rol de jugador
        Role::create(['name' => 'Player']);

        //Permisos para administradores
        Permission::create(['name' => 'Crear formación']);
        Permission::create(['name' => 'Añadir jugador']);
        Permission::create(['name' => 'Editar jugador']);
        Permission::create(['name' => 'Borrar jugador']);
        Permission::create(['name' => 'Ver usuarios']);

        //Permiso para admin y player
        Permission::create(['name' => 'Ver jugador']);
        Permission::create(['name' => 'Ver formación']);

        //Asignar permisos a los roles
        $admin = Role::findByName('Admin');
        $admin->givePermissionTo([
            'Crear formación',
            'Añadir jugador',
            'Editar jugador',
            'Borrar jugador',
            'Ver jugador',
            'Ver formación',
            'Ver usuarios'
        ]);

        $player = Role::findByName('Player');
        $player->givePermissionTo([
            'Ver jugador',
            'Ver formación'
        ]);
    }
}
