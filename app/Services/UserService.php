<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Utilities\ResponseError;

class UserService
{
    private $repository;
    private $error;

    public function __construct(
        UserRepository $repository,
        ResponseError  $responseError
    ) {
        $this->repository = $repository;
        $this->error = $responseError;
    }

    public function getAll()
    {
        $responseError = $this->error;

        $users = $this->repository
            ->getAll();

        if (isset($users->internalError)) {
            return $users;
        }

        if (!$users->count()) {
            $responseError->error = true;
            $responseError->msg = 'Users Not Found';
            $responseError->statusCode = 404;
            return $responseError;
        }

        return $users;
    }

    public function show(int $userId)
    {
        $responseError = $this->error;

        $user = $this->repository
            ->find($userId);

        if (isset($user->internalError)) {
            return $user;
        }

        if ($user == null) {
            $responseError->error = true;
            $responseError->msg = 'User Not Found';
            $responseError->statusCode = 404;
            return $responseError;
        }

        return $user;
    }

    public function create($userRequest)
    {
        $responseError = $this->error;

        $data = $this->repository
            ->validateUserExistsData($userRequest);

        if (isset($data->internalError)) {
            return $data;
        }

        if (isset($data->cpf)) {
            $responseError->error = true;
            $responseError->msg = 'cpf alredy exists';
            $responseError->statusCode = 400;
            return $responseError;
        }

        if (isset($data->email)) {
            $responseError->error = true;
            $responseError->msg = 'email alredy exists';
            $responseError->statusCode = 400;
            return $responseError;
        }

        $userCreate = $this->repository
            ->create($userRequest);

        return $userCreate;
    }

    public function update($userRequest)
    {
        $responseError = $this->error;
        $userRespository = $this->repository;

        $user = $userRespository->find($userRequest->id);

        if (isset($user->internalError)) {
            return $user;
        }

        if ($user == null) {
            $responseError->error = true;
            $responseError->msg = 'User Not Found';
            $responseError->statusCode = 404;
            return $responseError;
        }

        $data = $userRespository->validateUserExistsData($userRequest);

        if (isset($data->internalError)) {
            return $data;
        }

        if (isset($data->cpf)) {
            $responseError->error = true;
            $responseError->msg = 'cpf alredy exists';
            $responseError->statusCode = 400;
            return $responseError;
        }

        if (isset($data->email)) {
            $responseError->error = true;
            $responseError->msg = 'email alredy exists';
            $responseError->statusCode = 400;
            return $responseError;
        }

        $update = $userRespository->update($userRequest);

        return $update;
    }

    public function delete(int $userId)
    {
        $responseError = $this->error;
        $userRespository = $this->repository;

        $user = $userRespository->find($userId);

        if (isset($user->internalError)) {
            return $user;
        }

        if ($user == null) {
            $responseError->error = true;
            $responseError->msg = 'User Not Found';
            $responseError->statusCode = 404;
            return $responseError;
        }

        $userDelete = $this->repository
            ->delete($userId);

        if (isset($userDelete->internalError)) {
            $userDelete;
        }

        return $userDelete;
    }
}
