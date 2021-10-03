<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    private $repository;

    public function __construct(
        UserRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function getAll()
    {
        $users = $this->repository
            ->getAll();

        return $users;
    }

    public function show(int $userId)
    {
        $user = $this->repository
            ->find($userId);

        return $user;
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

    public function update($userRequest)
    {
        $userExits = $this->repository
            ->find($userRequest->id);

        if ($userExits->error) {
            return $userExits;
        }

        $userData = $this->repository
            ->validadeUserExistsData($userRequest);

        if (isset($userData)) {
            return $userData;
        }

        $update = $this->repository
            ->update($userRequest);

        return $update;
    }

    public function delete(int $userId)
    {
        $userDelete = $this->repository
            ->delete($userId);

        return $userDelete;
    }
}
