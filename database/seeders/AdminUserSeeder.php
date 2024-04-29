<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->firstOrCreate([
            'email' => 'admin@mail.com',
        ], [
            'name' => 'First Admin User',
            'password' => bcrypt('password'),
        ]);
        $user->roles()->sync(Role::ADMIN->value);
    }
}
