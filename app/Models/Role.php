<?php

namespace App\Models;

use App\Enums\Role as RoleEnum;
use App\Enums\RoleLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property \App\Enums\Role $role
 * @property \App\Enums\RoleLevel $level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    protected $primaryKey = 'role';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'role',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'role' => RoleEnum::class,
            'level' => RoleLevel::class,
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|\App\Models\User
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role')
            ->using(RoleUser::class);
    }
}
