<?php

namespace Tests\Feature\Controllers;

use App\Actions\Auth\IssueToken;
use App\Http\Requests\TokenIssueFormRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testValidData()
    {
        $user = User::factory()->create();
        // Arrange
        $payload = [
            'email' => $user->email,
            'password' => $user->password,
            'device_name' => 'device',
        ];

        $this->instance(
            IssueToken::class,
            Mockery::mock(IssueToken::class, fn (MockInterface $mock) => (
                $mock->shouldReceive('execute')
                    ->once()
                    ->andReturn('token')
            ))
        );

        // Act
        $response = $this->postJson(route('auth.token'), $payload);
        // $response->dd();

        // Assert
        $response->assertStatus(200);
    }

    public function testInvalidData()
    {
        // Arrange
        $payload = [
            'email' => 'test',
            'password' => '',
            'device_name' => 'device',
        ];

        // Act
        $response = $this->postJson(route('auth.token'), $payload);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }

    public function testEmailMustExistInUsersTable()
    {
        // Arrange
        $payload = [
            'email' => 'nonexistent@example.com',
            'password' => 'password',
            'device_name' => 'device',
        ];

        // Act
        $response = $this->postJson(route('auth.token'), $payload);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function testIssueToken()
    {
        $data = [
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
            'device_name' => $this->faker->word(),
        ];

        $this->instance(
            IssueToken::class,
            Mockery::mock(IssueToken::class, fn (MockInterface $mock) => (
                $mock->shouldReceive('execute')
                    ->withArgs([$data])
                    ->once()
                    ->andReturn('token')
            ))
        );
        $this->instance(
            TokenIssueFormRequest::class,
            Mockery::mock(TokenIssueFormRequest::class, fn (MockInterface $mock) => (
                $mock->shouldReceive('validated')->andReturn($data)
            ))
        );

        // Act
        $response = $this->postJson(route('auth.token'), $data);
        // Assert
        $response->assertStatus(200);
        $response->assertJson(['type' => 'Bearer', 'token' => 'token']);
    }
}
