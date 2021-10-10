<?php

namespace App\Repositories;

use App\Models\Bank;
use App\Models\Queue;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Utilities\ResponseError;
use phpDocumentor\Reflection\Types\Null_;

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

    public function transferRollback($payerId, $value, $payeeId)
    {
        try {
            $bank = $this->bankModel;

            if ($payerId != null) {
                $userAccount = $bank->find($payerId);
                $userAccount->money += $value;
                $userAccount->save();
            }

            if ($payeeId != null) {
                $userAccount = $bank->find($payeeId);
                $userAccount->money -= $value;
                $userAccount->save();
            }
        } catch (\Exception $err) {
            $this->responseError->error = true;
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function transferOut($transferRequest)
    {
        try {
            $bank = $this->bankModel;
            $payer = $bank->find($transferRequest->payer);
            $payer->money -= $transferRequest->value;
            $payer->save();
        } catch (\Exception $err) {
            $this->responseError->error = true;
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function transferIn($transferRequest)
    {
        try {
            $bank = $this->bankModel;
            $payee = $bank->find($transferRequest->payee);
            $payee->money += $transferRequest->value;
            $payee->save();
        } catch (\Exception $err) {
            $this->responseError->error = true;
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function transactions($transferRequest)
    {
        try {
            $transaction = $this->transaction;
            $trasanctionSave = $transaction::create(
                [
                    "payer_id" => $transferRequest->payer,
                    "payee_id" => $transferRequest->payee,
                    "value" => $transferRequest->value
                ]
            );
            return $trasanctionSave;
        } catch (\Exception $err) {
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
