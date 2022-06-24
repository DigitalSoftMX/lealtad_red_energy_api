<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * La función product() devuelve todos los productos que pertenecen a una categoría
     * @return A collection of products
     */
    public function products(){
        return $this->hasMany(Product::class);
    }
}
