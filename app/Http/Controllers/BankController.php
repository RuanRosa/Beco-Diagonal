<?php

namespace App\Http\Controllers;

use App\Http\Resources\Accounts;
use App\Services\BankService;
use App\Utilities\BankBodyValidator;
use Illuminate\Http\Request;

/**
 * Suppress all warnings from these two rules.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */

class BankController extends Controller
{

    private $bankService;
    private $request;
    private $bankBodyValidator;

    public function __construct(
        BankService $bankService,
        Request $request,
        BankBodyValidator $bankBodyValidator
    ) {
        $this->bankService = $bankService;
        $this->request = $request;
        $this->bankBodyValidator = $bankBodyValidator;
    }

    private function validateError($dataError)
    {
        if (isset($dataError->internalError)) {
            return response()->json(
                ["error" => $dataError->internalError],
                500
            );
        }

        if (isset($dataError->error)) {
            return response()->json(
                ["error" => $dataError->msg],
                $dataError->statusCode
            );
        }
    }

    public function getAll()
    {
        $accounts = $this->bankService
            ->getAll();

        if (isset($accounts->error)) {
            return $this->validateError($accounts);
        }

        $dto = Accounts::collection($accounts);

        return response()->json(
            $dto,
            200
        );
    }

    public function deposit()
    {
        $bodyErr = $this->bankBodyValidator
            ->bankDeposit($this->request);

        if ($bodyErr) {
            return response()->json(
                $bodyErr->errors(),
                400
            );
        }

        $accountDeposit = $this->bankService
            ->deposit($this->request);

        if (isset($accountDeposit->error)) {
            return $this->validateError($accountDeposit);
        }

        $dto = new Accounts($accountDeposit);

        return response()->json(
            $dto,
            201
        );
    }
}
