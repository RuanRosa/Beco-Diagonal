<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Validator;

class BodyValidators
{
    public function User($userRequest) {
        $validator = Validator::make($userRequest->all(), [
            'name' => 'required',
            'email' => 'required',
            'cpf' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $validator;
        }
    }

}
