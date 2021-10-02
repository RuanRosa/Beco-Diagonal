<?php

namespace App\Services;

use App\Repositories\UserRepository;
use http\Env\Request;
use http\Exception;

class UserService
{
    private $repository;

    public function __construct(
        UserRepository $repository
    )
    {
        $this->repository = $repository;
    }

    public function GetAll()
    {
        $users = $this->repository->GetAll();

        return $users;
    }

    public function Create($userRequest)
    {
        $userData = $this->repository->validadeUserExistsData($userRequest);

        if (isset($userData)) {
            return $userData;
        }

        $users = $this->repository->Create($userRequest);

        return $users;
    }
}
