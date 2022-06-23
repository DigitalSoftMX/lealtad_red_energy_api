<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'image',
        'user_id',
        'points',
        'double_points',
        'terms_and_conditions'
    ];
}
