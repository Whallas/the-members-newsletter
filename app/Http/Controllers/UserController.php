<?php

namespace App\Http\Controllers;

use App\Actions\User\CreateReader;
use App\Http\Requests\UserFormRequest;
use App\Http\Resources\UserResource;
use Knuckles\Scribe\Attributes\Group;

#[Group("Gerenciamento de usuários")]
class UserController extends Controller
{
    /**
     * Criar um novo usuário leitor
     *
     * @bodyParam password_confirmation string required Confirmação de senha
     */
    public function createReader(UserFormRequest $request, CreateReader $createReader)
    {
        $user = $createReader->execute($request->validated());

        return new UserResource($user);
    }
}
