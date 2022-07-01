<?php

use App\Models\Client;
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
            'name'          => 'Super',
            'first_surname' => 'Admin',
            'second_surname'=> 'Master',
            'email'         => 'adminmaster@digitalsoft.mx',
            'sex'           => 'Hombre',
            'phone'         => '2221919027',
            'birthday'     => '1994-05-12',
            'job'           => 'EL ADMIN MASTER',
            'active'        => 'ACTIVE',
            'password'      => bcrypt('adminmaster1234'),
        ]);
        DB::table('role_user')->insert(['user_id'=>$user->id,'role_id'=>1]);
        $client = Client::create([
            'membership'=> 'adminMaster',
            'points'    => 1000,
            'address'   => 'Cerrada Puebla, Pue.',
            'active'    => 'ACTIVE',
            'image'     => NULL,
            'user_id'   => $user->id,
        ]);
        DB::table('user_client')->insert(['user_id'=>$user->id,'client_id'=>$client->id]);

        $user = User::create([
            'name'          => 'Administrador',
            'first_surname' => 'Digital',
            'second_surname'=>  'Mx',
            'email'         => 'admin@digitalsoft.mx',
            'sex'           => 'Mujer',
            'phone'         => '2234123289',
            'birthday'     => '1994-05-10',
            'job'           => 'EL ADMINISTRADOR',
            'active'        => 'ACTIVE',
            'password'      => bcrypt('admin1234'),
        ]);
        DB::table('role_user')->insert(['user_id'=>$user->id,'role_id'=>2]);

        $user = User::create([
            'name'          => 'Admin',
            'first_surname' => 'Sucursal',
            'second_surname'=> 'Mx',
            'email'         => 'sucursal@digitalsoft.mx',
            'sex'           => 'Hombre',
            'phone'         => '2234123289',
            'birthday'     => '1994-05-10',
            'job'           => 'EL ADMIN SUCURSAL',
            'password'      => bcrypt('estacion1234'),
            'remember_token'=> NULL,
            'external_id'   => NULL,
        ]);
        DB::table('role_user')->insert(['user_id'=>$user->id,'role_id'=>3]);

        $user = User::create([
            'name'          => 'Enrique',
            'first_surname' => 'Perez',
            'second_surname'=> 'Aguilae',
            'email'         => 'usuario@digitalsoft.mx',
            'sex'           => 'Hombre',
            'phone'         => '2225342171',
            'birthday'     => '1994-05-10',
            'job'           => 'EL ADMIN SUCURSAL',
            'password'      => bcrypt('usuario1234'),
        ]);
        DB::table('role_user')->insert(['user_id'=>$user->id,'role_id'=>4]);
        $client = Client::create([
            'membership'    => 'E21133322',
            'points'        => 100,
            'address'       => 'rivera del atoyac sur 1703',
            'image'         => NULL,
            'active'        => 'ACTIVE',
            'user_id'       => $user->id,
            'ids'           => NULL,
        ]);
        DB::table('user_client')->insert(['user_id'=>$user->id,'client_id'=>$client->id]);
        // print_r(json_encode($user));
    }

}
