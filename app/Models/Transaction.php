<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = "transactions";

    public $fillable = [
        'payer_id',
        'payee_id',
        'value',
    ];

    protected $casts = [
        'id' => 'integer',
        'payer_id' => 'integer',
        'payee_id' => 'integer',
        'value' => 'float',
    ];
}
