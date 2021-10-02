<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Utilities\BodyValidators;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $request;
    private $service;
    private $bodyValidators;

    public function __construct(
        Request        $request,
        UserService    $service,
        BodyValidators $bodyValidators
    )
    {
        $this->request = $request;
        $this->service = $service;
        $this->bodyValidators = $bodyValidators;
    }

    public function getAll()
    {
        $users = $this->service
            ->getAll();

        if (isset($users->error)) {
            return response()->json(
                ["error" => $users->error], $users->statusCode
            );
        }

        return response()->json(
            $users, 200
        );
    }

    public function show()
    {
        $user = $this->service
            ->show($this->request->id);

        if (isset($user->error)) {
            return response()->json(
                ["error" => $user->error], $user->statusCode
            );
        }

        return response()->json(
            $user, 200
        );
    }

    public function create()
    {
        $body = $this->bodyValidators
            ->User($this->request);

        if ($body) {
            return response()->json(
                $body->errors(), 400
            );
        }

        $users = $this->service
            ->create($this->request);

        if (isset($users->error)) {
            return response()->json(
                ["error" => $users->error], $users->statusCode
            );
        }

        return response()->json(
            $users, 200
        );
    }

    public function delete()
    {
        $userId = $this->request->id;

        $user = $this->service
            ->delete($userId);

        if (isset($user->error)) {
            return response()->json(
                ["error" => $user->error], $user->statusCode
            );
        }

        return response()->json(
            $user, 200
        );
    }
}
