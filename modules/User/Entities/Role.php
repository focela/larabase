<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\User\Entities;

use Modules\User\Helpers\Permission;
use Focela\Laratrust\Roles\EloquentRole;
use Modules\Support\Eloquent\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends EloquentRole
{
    use Translatable;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    protected $translatedAttributes = ['name'];

    /**
     * Get a list of all roles.
     *
     * @return array
     */
    public static function list(): array
    {
        return static::select('id')->get()->pluck('name', 'id');
    }

    /**
     * The users' relationship.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_users', 'role_id', 'user_id');
    }

    /**
     * Set role's permissions.
     *
     * @param array $permissions
     *
     * @return void
     */
    public function setPermissionsAttribute(array $permissions): void
    {
        $this->attributes['permissions'] = Permission::prepare($permissions);
    }
}
