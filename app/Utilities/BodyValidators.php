<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Validator;

class BodyValidators
{
    private $validator;

    public function __construct(
        Validator $validator
    ) {
        $this->validator = $validator;
    }

    public function user($userRequest)
    {
        $validator = $this->validator->make($userRequest->all(), [
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
