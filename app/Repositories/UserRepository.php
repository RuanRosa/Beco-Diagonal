<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;

class ResponseError
{
    public $error;
    public $statusCode;
}

class UserRepository
{
    private $model;
    private $responseError;

    public function __construct(
        User $user,
        ResponseError $responseError
    )
    {
        $this->model = $user;
        $this->responseError = $responseError;
    }

    public function GetAll()
    {
        try {
            $users = $this->model::all();
            return $users;
        }
        catch (\Exception $err) {
            $this->responseError->error = $err->getMessage();
            $this->responseError->statusCode = $err->getCode();

            return $this->responseError;
        }
    }
}
