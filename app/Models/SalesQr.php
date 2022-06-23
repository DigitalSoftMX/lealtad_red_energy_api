<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesQr extends Model
{
    protected $fillable = ['sale', 'gasoline_id', 'liters', 'points', 'payment', 'station_id', 'client_id', 'no_bomb', 'main_id', 'reference'];
    // Relacion con la estacion
    public function station()
    {
        return $this->belongsTo(Station::class);
    }
    // Relacion con el cliente
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
