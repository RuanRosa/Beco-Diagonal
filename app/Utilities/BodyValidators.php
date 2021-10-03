<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Validator;

class BodyValidators
{
    public function user($userRequest)
    {
        $validator = validator::make($userRequest->all(), [
            'name' => 'required',
            'email' => 'required',
            'cpf' => 'required',
            'password' => 'required',
            'role_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $validator;
        }
    }
}
