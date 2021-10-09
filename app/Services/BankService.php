<?php

namespace App\Services;

use App\Repositories\BankRepository;
use App\Utilities\ResponseError;

/**
 * Suppress all warnings from these two rules.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class BankService
{
    private $bankRepository;
    private $error;

    public function __construct(
        BankRepository $bankRepository,
        ResponseError $error
    ) {
        $this->bankRepository = $bankRepository;
        $this->erro = $error;
    }
    public function getAll()
    {
        $responseError = $this->error;

        $accounts = $this->bankRepository
            ->getAll();

        if (isset($accounts->internalError)) {
            return $accounts;
        }

        if (!$accounts->count()) {
            $responseError->error = true;
            $responseError->msg = 'the bank is empty';
            $responseError->statusCode = 404;
            return $responseError;
        }

        return $accounts;
    }

    public function deposit($request)
    {
        $responseError = $this->error;

        $bankRepository = $this->bankRepository;
        $bankAccount = $bankRepository->findBankAccount($request->account_id);

        if (isset($bankAccount->internalError)) {
            return $bankAccount;
        }

        if ($bankAccount == null) {
            $responseError->error = true;
            $responseError->msg = 'Account not found';
            $responseError->statusCode = 404;
            return $responseError;
        }

        $accountsDeposit = $bankRepository->deposit($request);

        if (isset($accountsDeposit->internalError)) {
            return $accountsDeposit;
        }


        return $accountsDeposit;
    }
}
