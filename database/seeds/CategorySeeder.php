<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name'          => 'gasolina',
            'description'   => 'Mi descripcion de gasolina'
        ]);
        Category::create([
            'name'          => 'galletas',
            'description'   => 'Mi descripcion de galletas'
        ]);
        Category::create([
            'name'          => 'refrescos',
            'description'   => 'Mi descripcion de refrescos'
        ]);
    }
}
