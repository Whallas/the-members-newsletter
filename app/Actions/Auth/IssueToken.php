<?php

namespace App\Actions\Auth;

use App\Actions\Bases\ActionBase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @method string execute(array $data = [])
 */
class IssueToken extends ActionBase
{
    protected function setParameters(array $data = []): void
    {
        $this->data = [
            'email' => $data['email'] ?? '',
            'password' => $data['password'] ?? '',
            'device_name' => $data['device_name'] ?? '',
        ];
    }

    protected function main()
    {
        /** @var ?User $user */
        $user = User::query()->where('email', $this->data['email'])->first();

        if (!$user || !Hash::check($this->data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        return $user->createToken($this->data['device_name'])->plainTextToken;
    }
}
