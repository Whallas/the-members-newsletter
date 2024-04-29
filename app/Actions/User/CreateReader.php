<?php

namespace App\Actions\User;

use App\Actions\Bases\ActionBase;
use App\Enums\Role;
use App\Models\User;

/**
 * @method \App\Models\User execute(array $data = [])
 */
class CreateReader extends ActionBase
{
    protected function setParameters(array $data = []): void
    {
        $this->data = [
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'password' => $data['password'] ?? '',
        ];
    }

    protected function main()
    {
        /** @var User $user */
        $user = User::create($this->data);
        $user->roles()->attach(Role::READER->value);

        return $user;
    }
}
