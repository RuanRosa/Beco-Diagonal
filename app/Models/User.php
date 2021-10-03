<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class User extends Model
{
    protected $table = "users";

    public function userRole()
    {
        return $this->hasOne(UserRole::class, 'user_id', 'id');
    }

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
