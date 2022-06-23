<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasoline extends Model
{
    protected $table = 'gasolines';

    protected $fillable = [
        'name'
    ];
}
