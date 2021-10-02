<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $fillable = [
        'name',
        'cpf',
        'email',
        'password'
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'cpf' => 'integer',
        'email' => 'string',
        'password' => 'string'
    ];
}
