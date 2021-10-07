<?php

namespace App\Utilities;

class MakeBankAccountObject
{
    public function make($request)
    {
        $accounts = array();
        $accounts = (object) $accounts;
        $accounts->payer = (object) array();
        $accounts->payer->user_id = $request->payer;
        $accounts->payer->type = 'payer';
        $accounts->payee = (object) array();
        $accounts->payee->user_id = $request->payee;
        $accounts->payee->type = 'payee';
        return $accounts;
    }
}
