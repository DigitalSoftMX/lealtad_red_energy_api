<?php

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::create([
            'name'  => 'Pieza'
        ]);
        Unit::create([
            'name'  => 'Kilo'
        ]);
        Unit::create([
            'name'  => 'Litro'
        ]);
        Unit::create([
            'name'  => 'Otro'
        ]);
    }
}
