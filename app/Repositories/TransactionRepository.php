<?php

namespace App\Repositories;

use App\Models\Bank;
use App\Models\Queue;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Utilities\ResponseError;

/**
 * Suppress all warnings from these two rules.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */

class TransactionRepository
{
    private $bankModel;
    private $responseError;
    private $transaction;
    private $queue;

    public function __construct(
        Bank $bankModel,
        ResponseError $responseError,
        Transaction $transactionModel,
        Queue $queue
    ) {
        $this->bankModel = $bankModel;
        $this->responseError = $responseError;
        $this->transaction = $transactionModel;
        $this->queue = $queue;
    }

    public function transferRollback($userAccountId, $value)
    {
        DB::beginTransaction();
        try {
            $bank = $this->bankModel;
            $userAccount = $bank->find($userAccountId);
            $userAccount->money = $userAccount->money + $value;
            $userAccount->save();
            DB::commit();
        } catch (\Exception $err) {
            DB::rollBack();
            $this->responseError->error = true;
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function transferOut($transferRequest)
    {
        DB::beginTransaction();
        try {
            $bank = $this->bankModel;
            $payer = $bank->find($transferRequest->payer);
            $payer->money = $payer->money - $transferRequest->value;
            $payer->save();
            DB::commit();
        } catch (\Exception $err) {
            DB::rollBack();
            $this->responseError->error = true;
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function transferIn($transferRequest)
    {
        DB::beginTransaction();
        try {
            $bank = $this->bankModel;
            $payee = $bank->find($transferRequest->payee);
            $payee->money = $payee->money + $transferRequest->value;
            $payee->save();
            DB::commit();
        } catch (\Exception $err) {
            DB::rollBack();
            $this->responseError->error = true;
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function transactions($transferRequest)
    {
        DB::beginTransaction();
        try {
            $transaction = $this->transaction;
            $trasanctionSave = $transaction::create(
                [
                    "payer_id" => $transferRequest->payer,
                    "payee_id" => $transferRequest->payee,
                    "value" => $transferRequest->value
                ]
            );
            DB::commit();
            return $trasanctionSave;
        } catch (\Exception $err) {
            DB::rollBack();
            $this->responseError->error = true;
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function notifyQueue($transactionId)
    {
        DB::beginTransaction();
        try {
            $this->queue::create(
                [
                    "transaction_id" => $transactionId,
                ]
            );

            DB::commit();
        } catch (\Exception $err) {
            DB::rollBack();
            $this->responseError->error = true;
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function haveBalance($payerId)
    {
        try {
            $haveBalance = $this->bankModel
                ->select('money')
                ->find($payerId);

            return $haveBalance->money;
        } catch (\Exception $err) {
            $this->responseError->error = true;
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }
}
