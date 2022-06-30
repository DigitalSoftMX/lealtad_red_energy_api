<?php

namespace App\Models;

use App\Models\Web\Island;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    /* Accediendo a la tabla station */
    protected $table = 'stations';

    protected $fillable = [
        'name',
        'abrev',
        'address',
        'phone',
        'email',
        'total_timbres',
        'total_facturas',
        'id_company',
        'number_station',
        'active',
        'lealtad',
        'dns',
        'ip',
        'fail',
        'image',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    // Relacino con las islas y bombas de la estacion
    public function islands()
    {
        return $this->hasMany(Island::class);
    }
    // Relacion con los vales de la estacion
    public function voucher()
    {
        return $this->hasOne(Voucher::class, 'id_station', 'id');
    }
    // Relacion con el rango de vales de la estacion
    public function vouchers()
    {
        return $this->hasMany(CountVoucher::class, 'id_station', 'id');
    }
    // Relacion con los vales
    public function exchanges()
    {
        return $this->hasMany(Exchange::class);
    }
}
