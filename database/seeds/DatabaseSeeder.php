<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UnitSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            StatusSeeder::class,

            RoleSeeder::class,
            UserSeeder::class,

            CompanySeeder::class,
            StationSeeder::class,

        ]);
    }
}
