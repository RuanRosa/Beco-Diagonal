<?php

namespace App\Services;

use App\Repositories\BankRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Utilities\MakeBankAccountObject;
use App\Utilities\ResponseError;
use Illuminate\Support\Facades\Http;

/**
 * Suppress all warnings from these two rules.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class TransactionService
{
    private $transRepository;
    private $responseError;
    private $bankRepository;
    private $bankAccountObject;
    private $userRepository;

    public function __construct(
        TransactionRepository $transRepository,
        BankRepository $bankRepository,
        ResponseError $responseError,
        MakeBankAccountObject $bankAccountObject,
        UserRepository $userRepository
    ) {
        $this->transRepository = $transRepository;
        $this->responseError = $responseError;
        $this->bankRepository = $bankRepository;
        $this->bankAccountObject = $bankAccountObject;
        $this->userRepository = $userRepository;
    }

    public function transfer($request)
    {
        $responseError = $this->responseError;

        $rules = $this->rules($request, $responseError);

        if (isset($rules->error)) {
            return $rules;
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

        $transferAutorization = $this->transferAutorization();

        if (isset($transferAutorization->error)) {
            $this->transRepository
                ->transferRollback($request->payer, $request->value);
            return $transferAutorization;
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

        $notify = $this->notify();

        if (isset($notify->error)) {
            $this->transRepository
                ->notifyQueue($transactionSave->id);

            $this->transRepository
                ->transferRollback($request->payer, $request->value);
            return $notify;
        }
    }

    private function transferAutorization()
    {
        $responseError = $this->responseError;
        $transferAutorization = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        $autorized = 'Autorizado';
        $statusOk = 200;
        if ($transferAutorization->status() != $statusOk) {
            $responseError->error = true;
            $responseError->msg = 'transactions are currently disabled';
            $responseError->statusCode = $transferAutorization->status();
            return $responseError;
        }
        if ($transferAutorization['message'] != $autorized) {
            $responseError->error = true;
            $responseError->msg = 'unauthorized transaction';
            $responseError->statusCode = $transferAutorization->status();
            return $responseError;
        }
    }

    private function notify()
    {
        $responseError = $this->responseError;
        $notify = Http::get('http://o4d9z.mocklab.io/notify');
        $success = 'Success';
        $statusOk = 200;
        if ($notify->status() != $statusOk) {
            $responseError->error = true;
            $responseError->msg = 'notify error';
            $responseError->statusCode = $notify->status();
            return $responseError;
        }
        if ($notify['message'] != $success) {
            $responseError->error = true;
            $responseError->msg = 'notify error';
            $responseError->statusCode = $notify->status();
            return $responseError;
        }
    }

    private function rules($request, $responseError)
    {
        $payerRole = $this->userRepository
            ->find($request->payer);

        if ($payerRole->userRole->role_id == 1) {
            $responseError->error = true;
            $responseError->msg = 'shopkeepers cannot make transfers';
            $responseError->statusCode = 400;
            return $responseError;
        }

        $payerMoneyInBank = $this->transRepository
            ->haveBalance($request->payer);

        if ($request->value > $payerMoneyInBank) {
            $responseError->error = true;
            $responseError->msg = 'you have no balance to continue with the transaction.';
            $responseError->statusCode = 400;
            return $responseError;
        }
    }
}
