<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesQr extends Model
{
    protected $table = 'salesqrs';

    protected $fillable = [
        'tiket_id',
        'product_id',
        'cant',
        'points',
        'payment',
        'station_id',
        'client_id',
        'main_id',
        'reference'
    ];
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
