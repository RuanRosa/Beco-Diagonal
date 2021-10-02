<?php

namespace App\Services;

use App\Models\User;
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

    public function getAll()
    {
        $users = $this->repository
            ->getAll();

        return $users;
    }

    public function create($userRequest)
    {
        $userData = $this->repository
            ->validadeUserExistsData($userRequest);

        if (isset($userData)) {
            return $userData;
        }

        $users = $this->repository
            ->create($userRequest);

        return $users;
    }

    public function delete(int $userId)
    {
        $userDelete = $this->repository
            ->delete($userId);

        return $userDelete;
    }

    public function show(int $userId)
    {
        $user = $this->repository
            ->find($userId);
        
        return $user;
    }
}
