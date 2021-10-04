<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Utilities\ResponseError;

class TransactionService
{
    private $transactionRepository;
    private $responseError;

    public function __construct(
        TransactionRepository $TransactionRepository,
        ResponseError $responseError
    ) {
        $this->transactionRepository = $TransactionRepository;
        $this->responseError = $responseError;
    }

    public function transfer($transferRequest)
    {
        $transferRules = $this->transactionRepository
            ->rules($transferRequest);

        if (isset($transferRules->validateError)) {
            if ($transferRules->typeError == 'transferYourself') {
                $this->responseError->error = 'you can\'t transfer money to yourself';
                $this->responseError->statusCode = 400;
                return $this->responseError;
            }

        }

        $this->transactionRepository
            ->transfer($transferRequest);
    }
}
