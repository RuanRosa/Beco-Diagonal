<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Utilities\ResponseError;

class TransactionService
{
    private $transRepository;
    private $responseError;

    public function __construct(
        TransactionRepository $transRepository,
        ResponseError $responseError
    ) {
        $this->transRepository = $transRepository;
        $this->responseError = $responseError;
    }

    public function transfer()
    {
    }
}
