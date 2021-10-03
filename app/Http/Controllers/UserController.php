<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Utilities\BodyValidators;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $request;
    private $service;
    private $bodyValidators;
    private $userResource;

    public function __construct(
        Request $request,
        UserService $service,
        BodyValidators $bodyValidators
    ) {
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
                ["error" => $users->error],
                $users->statusCode
            );
        }


        $dto = UserResource::collection($users);

        return response()->json(
            $dto,
            200
        );
    }

    public function show()
    {
        $user = $this->service
            ->show($this->request->id);

        if (isset($user->error)) {
            return response()->json(
                ["error" => $user->error],
                $user->statusCode
            );
        }

        $dto = new UserResource($user);

        return response()->json(
            $dto,
            200
        );
    }

    public function create()
    {
        $bodyErr = $this->bodyValidators
            ->User($this->request);

        if ($bodyErr) {
            return response()->json(
                $bodyErr->errors(),
                400
            );
        }

        $user = $this->service
            ->create($this->request);

        if (isset($user->error)) {
            return response()->json(
                ["error" => $user->error],
                $user->statusCode
            );
        }

        $dto = new UserResource($user);

        return response()->json(
            $dto,
            200
        );
    }

    public function update()
    {
        $body = $this->bodyValidators
            ->User($this->request);

        if ($body) {
            return response()->json(
                $body->errors(),
                400
            );
        }

        $user = $this->service
            ->update($this->request);

        if (isset($user->error)) {
            return response()->json(
                ["error" => $user->error],
                $user->statusCode
            );
        }

        $dto = new UserResource($user);

        return response()->json(
            $dto,
            200
        );
    }

    public function delete()
    {
        $userId = $this->request->id;

        $user = $this->service
            ->delete($userId);

        if (isset($user->error)) {
            return response()->json(
                ["error" => $user->error],
                $user->statusCode
            );
        }

        $dto = new UserResource($user);

        return response()->json(
            $dto,
            200
        );
    }
}
