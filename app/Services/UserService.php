<?php

namespace App\Services;

use App\Repositories\UserRepository;
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
}
