<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;
use App\Utilities\ResponseUserError;

class UserRepository
{
    private $userModel;
    private $responseError;
    private $userRole;
    private $IDB;

    public function __construct(
        User $user,
        ResponseUserError $responseError,
        UserRole $userRoleModel,
        DB $IDB
    ) {
        $this->userModel = $user;
        $this->responseError = $responseError;
        $this->userRole = $userRoleModel;
        $this->IDB = $IDB;
    }

    public function getAll()
    {
        try {
            $users = $this->userModel
                ->get();

            foreach ($users as $user) {
                $user->userRole->roles;
            }

            if (!$users->count()) {
                $this->responseError->error = 'Users Not Found';
                $this->responseError->statusCode = 404;

                return $this->responseError;
            }
            return $users;
        } catch (\Exception $err) {
            $this->responseError->error = $err->getMessage();
            $this->responseError->statusCode = 500;

            return $this->responseError;
        }
    }

    public function create($userRequest)
    {
        $this->IDB
            ->beginTransaction();
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

            $this->userRole::create(
                [
                    "user_id" => $users->id,
                    "role_id" => $userRequest->role_id
                ]
            );

            foreach ($users as $user) {
                $users->userRole->roles;
            }

            $this->IDB
                ->commit();
            return $users;
        } catch (\Exception $err) {
            $this->IDB
                ->rollBack();
            $this->responseError->error = $err->getMessage();
            $this->responseError->statusCode = 500;

            return $this->responseError;
        }
    }

    public function update($userRequest)
    {
        $this->IDB
            ->beginTransaction();
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

            $this->IDB
                ->commit();
            return $user;
        } catch (\Exception $err) {
            $this->IDB
                ->rollBack();
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
        } catch (\Exception $err) {
            $this->responseError->error = $err->getMessage();
            $this->responseError->statusCode = 500;

            return $this->responseError;
        }
    }

    public function find(int $userId)
    {
        try {
            $user = $this->userModel->Find($userId);

            if ($user == null) {
                $this->responseError->exitsError = true;
                $this->responseError->error = 'User not found';
                $this->responseError->statusCode = 404;

                return $this->responseError;
            }
            $user->userRole->roles;

            return $user;
        } catch (\Exception $err) {
            $this->responseError->error = $err->getMessage();
            $this->responseError->statusCode = 500;

            return $this->responseError;
        }
    }

    public function delete(int $userId)
    {
        $this->IDB
            ->beginTransaction();
        try {
            $user = $this->userModel
                ->find($userId);

            if (!$user) {
                $this->responseError->error = "User Not Found";
                $this->responseError->statusCode = 404;

                return $this->responseError;
            }
            $user->userRole->roles;
            $user->delete();
            $this->IDB
                ->commit();
            return $user;
        } catch (\Exception $err) {
            $this->IDB
                ->rollBack();
            $this->responseError->error = $err->getMessage();
            $this->responseError->statusCode = 500;

            return $this->responseError;
        }
    }
}
