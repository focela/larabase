<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\User\Entities;

use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Modules\User\Helpers\Permission;
use Focela\Laratrust\Users\EloquentUser;
use Focela\Laratrust\Laravel\Facades\Activation;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Focela\Laratrust\Activations\EloquentActivation;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableInterface;

class User extends EloquentUser implements AuthenticatableInterface
{
    use Authenticatable, HasApiTokens, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'username',
        'phone',
        'avatar',
        'password',
        'permissions',
    ];

    /**
     * Array of login column names.
     *
     * @var array
     */
    protected $loginNames = [
        'email',
        'username',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_login'];

    /**
     * Check if the current user is activated.
     *
     * @return bool
     */
    public function isActivated(): bool
    {
        return Activation::completed($this);
    }

    /**
     * Get the active of the user.
     *
     * @return HasOne
     */
    public function activation(): HasOne
    {
        return $this->hasOne(EloquentActivation::class);
    }

    /**
     * Get the roles of the user.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_users');
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
