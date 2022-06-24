<?php

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name'          => 'premium',
            'description'   => 'Mi descripcion premiun',
            'barcode'       => '12345',
            'cost'          => 20.1,
            'stock'         => 1321,
            'price'         => 21.2,
            'points'        => 100,
            'alerts'        => 10,
            'image'         => NULL,
            'unit_id'       => 3,
            'category_id'   => 1,
        ]);
        Product::create([
            'name'          => 'premium',
            'description'   => 'Mi descripcion premiun',
            'barcode'       => '12345',
            'cost'          => 20.1,
            'stock'         => 1321,
            'price'         => 21.2,
            'points'        => 100,
            'alerts'        => 10,
            'image'         => NULL,
            'unit_id'       => 3,
            'category_id'   => 1,
        ]);
        Product::create([
            'name'          => 'premium',
            'description'   => 'Mi descripcion premiun',
            'barcode'       => '12345',
            'cost'          => 20.1,
            'stock'         => 1321,
            'price'         => 21.2,
            'points'        => 100,
            'alerts'        => 10,
            'image'         => NULL,
            'unit_id'       => 3,
            'category_id'   => 1,
        ]);
    }
}
