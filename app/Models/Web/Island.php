<?php

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;

class Island extends Model
{
    protected $table = 'islands';

    protected $fillable = [
        'statin_id',
        'island',
        'bomb'
    ];
}
