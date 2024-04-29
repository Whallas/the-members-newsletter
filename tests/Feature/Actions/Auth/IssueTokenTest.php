<?php

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\IssueToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class IssueTokenTest extends TestCase
{
    use RefreshDatabase;

    public function testIssueTokenWithValidCredentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'password'),
        ]);

        $issueToken = new IssueToken();

        $token = $issueToken->execute([
            'email' => $user->email,
            'password' => $password,
            'device_name' => 'test-device',
        ]);

        $this->assertNotNull($token);
    }

    public function testIssueTokenWithInvalidCredentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $issueToken = new IssueToken();

        $this->expectException(ValidationException::class);

        $issueToken->execute([
            'email' => $user->email,
            'password' => 'wrong-password',
            'device_name' => 'test-device',
        ]);
    }
}
