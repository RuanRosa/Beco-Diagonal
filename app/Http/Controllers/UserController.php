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

    private function validateError($dataError)
    {
        if (isset($dataError->internalError)) {
            return response()->json(
                ["error" => $dataError->internalError],
                500
            );
        }

        if (isset($dataError->error)) {
            return response()->json(
                ["error" => $dataError->msg],
                $dataError->statusCode
            );
        }
    }

    public function getAll()
    {
        $users = $this->service
            ->getAll();

        if (isset($users->error)) {
            return $this->validateError($users);
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
            return $this->validateError($user);
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

        if (isset($userCreate->error)) {
            return $this->validateError($userCreate);
        }

        $dto = new UserResource($userCreate);

        return response()->json(
            $dto,
            201
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

        if (isset($userUpdate->error)) {
            return $this->validateError($userUpdate);
        }

        $dto = new UserResource($userUpdate);

        return response()->json(
            $dto,
            201
        );
    }

    public function delete()
    {
        $userId = $this->request->id;

        $userDelete = $this->service
            ->delete($userId);

        if (isset($userDelete->error)) {
            return $this->validateError($userDelete);
        }

        $dto = new UserResource($userDelete);

        return response()->json(
            $dto,
            201
        );
    }
}
