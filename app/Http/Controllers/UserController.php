<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Utilities\UserBodyValidators;
use Illuminate\Http\Request;

/**
 * Suppress all warnings from these two rules.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */

class UserController extends Controller
{
    private $request;
    private $service;
    private $bodyValidators;

    public function __construct(
        Request $request,
        UserService $service,
        UserBodyValidators $bodyValidators
    ) {
        $this->request = $request;
        $this->service = $service;
        $this->bodyValidators = $bodyValidators;
    }

    public function getAll()
    {
        $users = $this->service
            ->getAll();

        if (isset($users->internalError)) {
            return response()->json(
                ["error" => $users->internalError],
                500
            );
        }

        if (isset($users->error)) {
            return response()->json(
                ["error" => $users->msg],
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

        if (isset($user->internalError)) {
            return response()->json(
                ["error" => $user->internalError],
                500
            );
        }

        if (isset($user->error)) {
            return response()->json(
                ["error" => $user->msg],
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

        $userCreate = $this->service
            ->create($this->request);


        if (isset($userCreate->internalError)) {
            return response()->json(
                ["error" => $userCreate->internalError],
                500
            );
        }

        if (isset($userCreate->error)) {
            return response()->json(
                ["error" => $userCreate->msg],
                $userCreate->statusCode
            );
        }

        $dto = new UserResource($userCreate);

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

        $userUpdate = $this->service
            ->update($this->request);

        if (isset($userUpdate->internalError)) {
            return response()->json(
                ["error" => $userUpdate->internalError],
                500
            );
        }

        if (isset($userUpdate->error)) {
            return response()->json(
                ["error" => $userUpdate->msg],
                $userUpdate->statusCode
            );
        }

        $dto = new UserResource($userUpdate);

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
