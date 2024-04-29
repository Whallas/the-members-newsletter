<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property \App\Enums\Role $role
 * @mixin \Eloquent
 */
class RoleUser extends Pivot
{
    protected function casts(): array
    {
        return [
            'role' => Role::class,
        ];
    }
}
