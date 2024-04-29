<?php

namespace App\Models;

use App\Enums\Role;
use App\Enums\RoleLevel;
use App\Models\Role as RoleModel;

trait HasRoleLevels
{
    public function isAdmin() : bool
    {
        return $this->roles->contains('role', Role::ADMIN);
    }

    public function minReader() : bool
    {
        return $this->roles->filter(
            fn (RoleModel $role) => $role->level->value >= RoleLevel::READER->value
        )->isNotEmpty();
    }
}
