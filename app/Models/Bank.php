<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = "bank";

    public $fillable = [
        'user_id',
        'money',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'money' => 'float',
    ];
}
