<?php

namespace App\Models;

use App\Models\Status;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    protected $fillable = [
        'points',
        'value',
        'status_id',
        'client_id',
        'station_id',
        'user_by',//Quien crea el canje
    ];
    // Relacion con las estaciones
    public function station()
    {
        return $this->belongsTo(Station::class);
    }
    // Relacion con el status
    public function estado()
    {
        return $this->belongsTo(Status::class, 'status', 'id');
    }
}
