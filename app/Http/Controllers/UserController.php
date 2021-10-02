<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $request;

    private $service;

    public function __construct(
            Request $request,
            UserService $service
        )
    {
        $this->request = $request;
        $this->service = $service;
    }

    public function GetAll()
    {
        $users = $this->service->GetAll();

        if (isset($users->error)) {
            return response()->json(
                $users->error, $users->statusCode
            );
        }

        return response()->json(
            $users, 200
        );
    }
}
