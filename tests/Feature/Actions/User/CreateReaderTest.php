<?php

namespace Tests\Feature\Actions\User;

use App\Actions\User\CreateReader;
use App\Enums\Role;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateReaderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function testCreateReader()
    {
        $data = [
            'name' => 'Test Reader',
            'email' => 'reader@test.com',
            'password' => 'password',
        ];

        $createReader = new CreateReader();

        $user = $createReader->execute($data);

        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertTrue(app('hash')->check($data['password'], $user->password));
        $this->assertTrue($user->roles->contains('role', Role::READER));

        // Check if the items are in the database
        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role' => Role::READER->value,
        ]);
    }
}
