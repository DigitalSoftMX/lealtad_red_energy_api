<?php

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/devUtils/status.json");
        $data = json_decode($json);
        // print_r(json_encode($data));
        foreach ($data as $obj) {
            Status::create([
                'name'  => $obj->name,
            ]);
        }
    }
}
