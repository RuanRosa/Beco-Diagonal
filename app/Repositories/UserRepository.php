<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Utilities\ResponseError;

class ResponseUserError
{
    public string $error;
    public int $statusCode;
}

class UserRepository
{
    private $userModel;
    private $responseError;

    public function __construct(
        User $user,
        ResponseUserError $responseError
    )
    {
        $this->userModel = $user;
        $this->responseError = $responseError;
    }

    public function GetAll()
    {
        try {
            $users = $this->userModel::all();
            return $users;
        }
        catch (\Exception $err) {
            $this->responseError->error = $err->getMessage();
            $this->responseError->statusCode = 500;

            return $this->responseError;
        }
    }

    public function Create($userRequest)
    {
        DB::beginTransaction();
        try {

            $users = $this->userModel;

            $users = $users::create(
                [
                    "name" => $userRequest->name,
                    "cpf" => $userRequest->cpf,
                    "email" => $userRequest->email,
                    "password" => $userRequest->password
                ]
            );

            DB::commit();
            return $users;
        }
        catch (\Exception $err) {
            DB::rollBack();
            $this->responseError->error = $err->getMessage();
            $this->responseError->statusCode = 500;

            return $this->responseError;
        }
    }

    public function validadeUserExistsData($userRequest)
    {
        try {
            $cpfValidate = $this->userModel
                ->where('cpf', $userRequest->cpf)
                ->get()
                ->count();

            if ($cpfValidate) {
                $this->responseError->error = 'CPF alredy exists';
                $this->responseError->statusCode = 400;

                return $this->responseError;
            }

            $emailValidate = $this->userModel
                ->where('email', $userRequest->email)
                ->get()
                ->count();

            if ($emailValidate) {
                $this->responseError->error = 'E-mail alredy exists';
                $this->responseError->statusCode = 400;

                return $this->responseError;
            }

        }
        catch (\Exception $err) {
            $this->responseError->error = $err->getMessage();
            $this->responseError->statusCode = 500;

            return $this->responseError;
        }
    }
}
