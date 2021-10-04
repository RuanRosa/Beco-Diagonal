<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Validator;

/**
 * Suppress all warnings from these two rules.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */

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
