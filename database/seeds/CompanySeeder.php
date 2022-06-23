<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Company::create([
            'name'                  => 'Eucomb',
            'address'               => 'Zona alta de Tehuacán',
            'phone'                 => '2222222222',
            'image'                 => NULL,
            'user_id'               => User::where('username','LIKE','%admin_eucomb%')->first()->id,
            'points'                => 100,
            'double_points'         => 1,
            'terms_and_conditions'  => NULL
        ]);
    }
}
