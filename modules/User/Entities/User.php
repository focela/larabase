<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\User\Entities;

use Focela\Laratrust\Activations\EloquentActivation;
use Focela\Laratrust\Laravel\Facades\Activation;
use Focela\Laratrust\Users\EloquentUser;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Passport\HasApiTokens;
use Modules\User\Helpers\Permission;

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
