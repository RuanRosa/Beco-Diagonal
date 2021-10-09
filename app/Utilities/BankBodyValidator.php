<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Validator;

/**
 * Suppress all warnings from these two rules.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */

class BankBodyValidator
{
    public function bankDeposit($bankRequest)
    {
        $validator = validator::make($bankRequest->all(), [
            'account_id' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator;
        }
    }
}
