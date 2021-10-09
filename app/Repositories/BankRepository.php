<?php

namespace App\Repositories;

use App\Models\Bank;
use App\Utilities\ResponseError;

class BankRepository
{
    private $bankModel;
    private $responseError;

    public function __construct(
        Bank $bankModel,
        ResponseError $responseError
    ) {
        $this->bankModel = $bankModel;
        $this->responseError = $responseError;
    }

    public function findBankAccount($userId)
    {
        try {
            $bank = $this->bankModel
                ->Find($userId);

            return $bank;
        } catch (\Exception $err) {
            $this->responseError->error = true;
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function getAll()
    {
        try {
            $bankAccounts = $this->bankModel
                ->All();

            foreach ($bankAccounts as $bankAccount) {
                $bankAccount->user;
            }

            return $bankAccounts;
        } catch (\Exception $err) {
            $this->responseError->error = true;
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function deposit($request)
    {
        try {
            $bankAccount = $this->bankModel
                ->find($request->account_id);

            $bankAccount->money += $request->value;
            $bankAccount->save();

            $bankAccount->user;

            return $bankAccount;
        } catch (\Exception $err) {
            $this->responseError->error = true;
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }
}
