<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $table = "queue";

    public $fillable = [
        'transaction_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'transaction_id' => 'integer',
    ];
}
