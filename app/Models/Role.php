<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Role extends Model
{
    protected $table = "roles";

    public function roleUser()
    {
        return $this->belongsTo(UserRole::class, 'role_io', 'id');
    }


    public $fillable = [
        'type'
    ];

    protected $casts = [
        'id' => 'integer',
        'type' => 'string',
    ];

}
