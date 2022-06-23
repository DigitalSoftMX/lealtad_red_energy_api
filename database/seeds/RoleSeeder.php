<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name'          => 'admin_master',
            'description'   => 'Administrador de la empresa DigitalSoft',
            'display_name'  => 'Administrador Master',
        ]);
        Role::create([
            'name'          => 'admin_eucomb',
            'description'   => 'Administrador de la empresa Eucomb',
            'display_name'  => 'Administrador Eucomb',
        ]);
        Role::create([
            'name'          => 'admin_estacion',
            'description'   => 'Administrador Eucomb para Vales y Premios',
            'display_name'  => 'Administrador Eucomb Vales y Premios',
        ]);
        Role::create([
            'name'          => 'usuario',
            'description'   => 'Usuarios',
            'display_name'  => 'Usuarios o Clientes',
        ]);
    }
}
