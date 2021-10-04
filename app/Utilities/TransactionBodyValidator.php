<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Validator;

class TransactionBodyValidator
{
    public function transaction($transactionRequest)
    {
        $validator = validator::make($transactionRequest->all(), [
            'value' => 'required',
            'payer' => 'required',
            'payee' => 'required'
        ]);

        if ($validator->fails()) {
            return $validator;
        }
    }
}
