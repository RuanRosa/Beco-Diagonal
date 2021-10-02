<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Utilities\ResponseError;

class ResponseUserError
{
    public bool $exitsError;
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

    public function getAll()
    {
        try {
            $users = $this->userModel::all();

            if (!$users->count()) {
                $this->responseError->error = 'Users Not Found';
                $this->responseError->statusCode = 404;

                return $this->responseError;
            }
            return $users;
        }
        catch (\Exception $err) {
            $this->responseError->error = $err->getMessage();
            $this->responseError->statusCode = 500;

            return $this->responseError;
        }
    }

    public function create($userRequest)
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

    public function update($userRequest)
    {
        DB::beginTransaction();
        try {
            $user = $this->userModel
                ->find($userRequest->id);

            $user->name = $userRequest->name;
            $user->cpf  = $userRequest->cpf;
            $user->email = $userRequest->email;
            $user->password = $userRequest->password;
            $user->save();

            DB::commit();
            return $user;

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
                ->first();


            if ($cpfValidate) {
                if ($cpfValidate->id == $userRequest->id) {
                    return null;
                }

                $this->responseError->error = 'CPF alredy exists';
                $this->responseError->statusCode = 400;

                return $this->responseError;
            }

            $emailValidate = $this->userModel
                ->where('email', $userRequest->email)
                ->first();


            if ($emailValidate) {
                if ($emailValidate->id == $userRequest->id) {
                    return null;
                }

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

    public function find(int $userId)
    {
        try {
            $user = $this->userModel::Find($userId);

            if ($user == null) {
                $this->responseError->exitsError = true;
                $this->responseError->error = 'User not found';
                $this->responseError->statusCode = 404;

                return $this->responseError;
            }
            return $user;

        }
        catch (\Exception $err) {
            $this->responseError->error = $err->getMessage();
            $this->responseError->statusCode = 500;

            return $this->responseError;
        }
    }

    public function delete(int $userId)
    {
        DB::beginTransaction();
        try {
            $user = $this->userModel
                ->find($userId);

            if (!$user) {
                $this->responseError->error = "User Not Found";
                $this->responseError->statusCode = 404;

                return $this->responseError;
            }

            $user->delete();
            DB::commit();
            return $user;

        }
        catch (\Exception $err) {
            DB::rollBack();
            $this->responseError->error = $err->getMessage();
            $this->responseError->statusCode = 500;

            return $this->responseError;
        }
    }
}
