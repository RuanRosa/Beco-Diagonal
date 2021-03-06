<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Suppress all warnings from these two rules.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'cpf'           => $this->email,
            'password'      => $this->password,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'role'          => [
                'id'    => $this->userRole->role_id,
                'type'  => $this->userRole->roles->type
            ]
        ];
    }
}
