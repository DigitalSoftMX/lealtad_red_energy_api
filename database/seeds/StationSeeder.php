<?php

use App\Models\Station;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/devUtils/stations.json");
        $data = json_decode($json);
        // print_r($data[0]);
        foreach($data as $obj){
            Station::create([
                'name'          => $obj->name,
                'abrev'         => $obj->abrev,
                'address'       => $obj->address,
                'phone'         => $obj->phone,
                'email'         => $obj->email,
                'total_timbres' => $obj->total_timbres,
                'total_facturas'=> $obj->total_facturas,
                'id_company'    => $obj->id_company,
                'number_station'=> $obj->number_station,
                'active'        => $obj->active,
                'lealtad'       => $obj->lealtad,
                'dns'           => $obj->dns,
                'ip'            => $obj->ip,
                'fail'          => $obj->fail,
                'image'         => $obj->image,
            ]);
        }
    }
}
