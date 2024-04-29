<?php

namespace Tests\Feature\Controllers;

use App\Actions\User\CreateReader;
use App\Http\Requests\UserFormRequest;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testUnauthenticatedUser()
    {
        $this->postJson(route('users.create_reader'))
            ->assertUnauthorized();
    }

    public function testOnlyAdminCanCreateReaders()
    {
        Sanctum::actingAs(User::factory()->asReader()->create());

        $this->postJson(route('users.create_reader'), [])
            ->assertForbidden();
    }

    public function testValidData()
    {
        Sanctum::actingAs(User::first());

        // Arrange
        $payload = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $user = $this->makeUser($payload);

        $this->instance(
            CreateReader::class,
            Mockery::mock(CreateReader::class, fn (MockInterface $mock) => (
                $mock->shouldReceive('execute')->once()
                    ->andReturn($user)
            ))
        );

        // Act
        $response = $this->postJson(route('users.create_reader'), $payload);

        // Assert
        $response->assertStatus(201);
    }

    public function testInvalidData()
    {
        Sanctum::actingAs(User::first());

        // Arrange
        $payload = [
            'name' => '',
            'email' => 'test',
            'password' => '',
            'password_confirmation' => '',
        ];

        // Act
        $response = $this->postJson(route('users.create_reader'), $payload);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function testCreateReader()
    {
        Sanctum::actingAs(User::first());

        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $user = $this->makeUser($data);

        $this->instance(
            UserFormRequest::class,
            Mockery::mock(UserFormRequest::class, fn (MockInterface $mock) => (
                $mock->shouldReceive('validated')->andReturn($data)
            ))
        );
        $this->instance(
            CreateReader::class,
            Mockery::mock(CreateReader::class, fn (MockInterface $mock) => (
                $mock->shouldReceive('execute')
                    ->withArgs([$data])
                    ->once()
                    ->andReturn($user)
            ))
        );

        // Act
        $response = $this->postJson(route('users.create_reader'), $data);
        // Assert
        $response->assertCreated();
    }

    private function makeUser(array $attributes): User
    {
        $user = new User($attributes);
        $user->id = $this->faker->randomNumber();
        $user->updateTimestamps();
        $user->wasRecentlyCreated = true;

        return $user;
    }
}
