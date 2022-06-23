<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name'          => 'Digital',
            'first_surname' => 'Soft',
            'second_surname'=> 'MX',
            'username'      => 'admin_master',
            'email'         => 'superadmin@digitalsoft.mx',
            'sex'           => 'Hombre',
            'phone'         => '2221919027',
            'address'       => 'Cerrada',
            'active'        => 1,
            'password'      => bcrypt('adminmaster1234'),
            'remember_token'=> NULL,
            'external_id'   => NULL,
        ]);
        DB::table('role_user')->insert([ 'user_id' => $user->id, 'role_id' => 1 ]);
        $user = User::create([
            'name'          => 'Administrador',
            'first_surname' => 'Eucomb',
            'second_surname'=>  NULL,
            'username'      => 'admin_eucomb',
            'email'         => 'admin@digitalsoft.mx',
            'sex'           => 'Mujer',
            'phone'         => '2234123289',
            'address'       => 'Cerrada',
            'active'        => 1,
            'password'      => bcrypt('admin1234'),
            'remember_token'=> NULL,
            'external_id'   => NULL,
        ]);
        DB::table('role_user')->insert([ 'user_id' => $user->id, 'role_id' => 2 ]);
        $user = User::create([
            'name'          => 'admin_estacion',
            'first_surname' => 'estacion',
            'second_surname'=> 'estacion',
            'username'      => 'admin_estacion',
            'email'         => 'estation@digitalsoft.mx',
            'sex'           => 'Hombre',
            'phone'         => '2234123289',
            'address'       => 'Cerrada',
            'active'        => 1,
            'password'      => bcrypt('estacion1234'),
            'remember_token'=> NULL,
            'external_id'   => NULL,
        ]);
        DB::table('role_user')->insert([ 'user_id' => $user->id, 'role_id' => 3 ]);

        $user = User::create([
            'name'          => 'enrique',
            'first_surname' => 'perez',
            'second_surname'=> 'aguilae',
            'username'      => 'E21133322',
            'email'         => 'usuario@digitalsoft.mx',
            'sex'           => 'Hombre',
            'phone'         => '2225342171',
            'address'       => 'rivera del atoyac sur 1703',
            'active'        => 1,
            'password'      => bcrypt('usuario1234'),
            'remember_token'=> NULL,
            'external_id'   => NULL,
        ]);

        DB::table('role_user')->insert([ 'user_id' => $user->id, 'role_id' => 4 ]);
        // print_r(json_encode($user));
    }

}
