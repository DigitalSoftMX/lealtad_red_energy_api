<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $table = 'units';

    protected $fillable = [
        'name'
    ];

    /**
     * > The `products()` function returns all the products that belong to the unit
     */
    public function products(){
        $this->hasMany(Product::class);
    }
}
