<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Enums\Role as RoleEnum;
use App\Enums\RoleLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->firstOrCreate([
            'role' => RoleEnum::ADMIN,
            'level' => RoleLevel::ADMIN,
        ]);
        Role::query()->firstOrCreate([
            'role' => RoleEnum::READER,
            'level' => RoleLevel::READER,
        ]);
    }
}
