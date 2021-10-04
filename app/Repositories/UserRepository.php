<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;
use App\Utilities\ResponseError;

class UserRepository
{
    private $userModel;
    private $responseError;
    private $userRole;
    private $transactionModel;

    public function __construct(
        User          $user,
        ResponseError $responseError,
        UserRole      $userRoleModel,
        Transaction   $transactionModel
    ) {
        $this->userModel = $user;
        $this->transactionModel = $transactionModel;
        $this->responseError = $responseError;
        $this->userRole = $userRoleModel;
    }

    public function getAll()
    {
        try {
            $users = $this->userModel
                ->get();

            foreach ($users as $user) {
                $user->userRole->roles;
            }

            return $users;
        } catch (\Exception $err) {
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function create($userRequest)
    {
        DB::beginTransaction();

        try {
            $user = $this->userModel;

            $user = $user::create(
                [
                    "name" => $userRequest->name,
                    "cpf" => $userRequest->cpf,
                    "email" => $userRequest->email,
                    "password" => $userRequest->password
                ]
            );

            $userRole = $this->userRole;

            $userRole::create(
                [
                    "user_id" => $user->id,
                    "role_id" => $userRequest->role_id
                ]
            );

            $transaction = $this->transactionModel;

            $transaction::create(
                [
                    "money" => 0,
                    "user_id" => $user->id,
                ]
            );

            $user->userRole->roles;

            DB::commit();
            return $user;
        } catch (\Exception $err) {
            DB::rollBack();
            $this->responseError->internalError = $err->getMessage();
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

            $userRole = $this->userRole
                ->where('user_id', $userRequest->id)
                ->first();

            $userRole->role_id = $userRequest->role_id;
            $userRole->save();

            $user->userRole->roles;

            DB::commit();
            return $user;
        } catch (\Exception $err) {
            DB::rollBack();
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function validateUserExistsData($userRequest)
    {
        try {
            $validate = $this->userModel;

            $cpf = $validate->where('cpf', $userRequest->cpf)
                ->first();

            if ($cpf != null) {
                if (isset($userRequest->id)) {
                    if ($userRequest->id == $cpf->id) {
                        return false;
                    }
                }
                return (object) ['cpf' => 'true'];
            }

            $email = $validate->where('email', $userRequest->email)
                ->first();

            if ($email != null) {
                if (isset($userRequest->id)) {
                    if ($userRequest->id == $email->id) {
                        return false;
                    }
                }
                return (object) ['email' => 'true'];
            }

            return false;

        } catch (\Exception $err) {
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function find(int $userId)
    {
        try {
            $user = $this->userModel->Find($userId);

            if ($user != null) {
                $user->userRole->roles;
            }

            return $user;
        } catch (\Exception $err) {
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }

    public function delete(int $userId)
    {
        DB::beginTransaction();
        try {
            $user = $this->userModel
                ->find($userId);
            $user->userRole->roles;
            $user->delete();
            DB::commit();
            return $user;
        } catch (\Exception $err) {
            DB::rollBack();
            $this->responseError->internalError = $err->getMessage();
            return $this->responseError;
        }
    }
}
