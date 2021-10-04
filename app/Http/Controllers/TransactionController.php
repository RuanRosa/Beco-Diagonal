<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Utilities\TransactionBodyValidator;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $transBodyValidator;
    private $request;
    private $transactionService;

    public function __construct(
        TransactionBodyValidator $transBodyValidator,
        Request $request,
        TransactionService $transactionService
    ) {
        $this->request = $request;
        $this->transBodyValidator = $transBodyValidator;
        $this->transactionService = $transactionService;
    }

    public function transfer()
    {
        $bodyErr = $this->transBodyValidator
            ->transaction($this->request);

        if ($bodyErr) {
            return response()->json(
                $bodyErr->errors(),
                400
            );
        }

        $transfer = $this->transactionService
            ->transfer($this->request);

        if (isset($transfer->error)) {
            return response()->json(
                ["error" => $transfer->error],
                $transfer->statusCode
            );
        }
    }
}
