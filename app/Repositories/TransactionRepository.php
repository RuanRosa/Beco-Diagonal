<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Utilities\ResponseError;
use Illuminate\Support\Facades\DB;

/**
 * Suppress all warnings from these two rules.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */

class TransactionRepository
{
    private $transactionModel;
    private $responseError;

    public function __construct(
        Transaction $transactionModel,
        ResponseError $responseError
    ) {
        $this->transactionModel = $transactionModel;
        $this->responseError = $responseError;
    }

    public function rules($transferRequest)
    {
        if ($transferRequest->payer == $transferRequest->payee) {
            $this->responseError->validateError = 'transferYourself';
            return $this->responseError;
        }

        $transactionModel = $this->transactionModel;
        $transactionModel->Find($transferRequest->payee);

        dd($transactionModel);
    }

    public function transfer($transferRequest)
    {
        DB::beginTransaction();
        try {
            DB::commit();
//            return $transaction;
        } catch (\Exception $err) {
            DB::rollBack();
            $this->responseError->error = $err->getMessage();
            $this->responseError->statusCode = 500;

            return $this->responseError;
        }
    }
}
