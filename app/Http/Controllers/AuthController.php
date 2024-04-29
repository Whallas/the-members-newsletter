<?php

namespace App\Http\Controllers;

use App\Actions\Auth\IssueToken;
use App\Http\Requests\TokenIssueFormRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Gerar um Token
     * @unauthenticated
     *
     * @throws ValidationException
     */
    public function issueToken(TokenIssueFormRequest $request, IssueToken $issueToken)
    {
        return response()->json([
            'type' => 'Bearer',
            'token' => $issueToken->execute($request->validated()),
        ]);
    }
}
