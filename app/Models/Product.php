<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'barcode',
        'cost',
        'price',
        'stock',
        'points',
        'alerts',
        'image',
        'unit_id',
        'category_id'
    ];

    /**
     * La función category() devuelve la categoría a la que pertenece el producto
     *
     * @return The category that belongs to the category.
     */
    public function category(){
        return $this->belongsTo(Category::class);
    }
    /**
     * La funcion unit() devuelve la unidad a la que pertenece el producto
     *
     * @return The unit that belongs to the product.
     */
    public function unit(){
        return $this->belongsTo(Unit::class);
    }
}
