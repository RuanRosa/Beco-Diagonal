<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Suppress all warnings from these two rules.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class Accounts extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'money' => $this->money,
            'user'  => [
                'id'            => $this->user->id,
                'name'          => $this->user->name,
                'cpf'           => $this->user->cpf,
                'email'         => $this->user->email,
                'created_at'    => $this->user->created_at,
                'updated_at'     => $this->user->updated_at
            ]
        ];
    }
}
