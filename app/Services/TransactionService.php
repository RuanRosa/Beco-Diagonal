<?php

namespace App\Services;

use App\Repositories\BankRepository;
use App\Repositories\TransactionRepository;
use App\Utilities\MakeBankAccountObject;
use App\Utilities\ResponseError;
use Illuminate\Support\Facades\Http;
use Mockery;
use Mockery\MockInterface;

/**
 * Suppress all warnings from these two rules.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class TransactionService
{
    private $transRepository;
    private $responseError;
    private $bankRepository;
    private $bankAccountObject;

    public function __construct(
        TransactionRepository $transRepository,
        BankRepository $bankRepository,
        ResponseError $responseError,
        MakeBankAccountObject $bankAccountObject
    ) {
        $this->transRepository = $transRepository;
        $this->responseError = $responseError;
        $this->bankRepository = $bankRepository;
        $this->bankAccountObject = $bankAccountObject;
    }

    private function transferAutorizare($request)
    {
        $responseError = $this->responseError;
        $transferAutorizare = Http::get(getenv('API_TRANSFER_AUTORIZARE'));
        $autorized = 'Autorizado';
        $statusOk = 200;
        if ($transferAutorizare->status() != $statusOk) {
            if ($transferAutorizare['message'] != $autorized) {
                $this->transRepository
                    ->transferRollback($request->payer, $request->value);
                $responseError->error = true;
                $responseError->msg = 'unauthorized transaction';
                $responseError->statusCode = $transferAutorizare->status();
                return $responseError;
            }

            $responseError->error = true;
            $responseError->msg = 'transactions are currently disabled';
            $responseError->statusCode = $transferAutorizare->status();
            return $responseError;
        }
    }

    public function transfer($request)
    {
        $responseError = $this->responseError;

        if ($request->payer == $request->payee) {
            $responseError->error = true;
            $responseError->msg = 'you can\'t transfer money to yourself';
            $responseError->statusCode = 400;
            return $responseError;
        }

        $accounts = $this->bankAccountObject
            ->make($request);

        foreach ($accounts as $account) {
            $bankRepository = $this->bankRepository;
            $bankAccount = $bankRepository->findBankAccount($account->user_id);

            if (isset($bankAccount->internalError)) {
                return $bankAccount;
            }
            if ($bankAccount == null) {
                $responseError->error = true;
                $responseError->msg = $account->type . ' Not Found';
                $responseError->statusCode = 404;
                return $responseError;
            }
        }

        $transferOut = $this->transRepository
            ->transferOut($request);

        if (isset($transferOut->internalError)) {
            return $transferOut;
        }

        $transferAutorizare = $this->transferAutorizare($request);

        if (isset($transferAutorizare->error)) {
            return $transferAutorizare;
        }

        $transferIn = $this->transRepository
            ->transferIn($request);

        if (isset($transferIn->internalError)) {
            $this->transRepository
                ->transferRollback($request->payer, $request->value);
            return $transferIn;
        }

        $transactionSave = $this->transRepository
            ->transactions($request);

        if (isset($transactionSave->error)) {
            return $transactionSave;
        }
    }
}
