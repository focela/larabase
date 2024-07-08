<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Entities\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! Role::exists()) {
            Role::create([
                'name'        => 'Administrator',
                'locked'      => true,
                'permissions' => $this->getAdminRolePermissions(),
            ]);

            Role::create([
                'name'   => 'Customer',
                'locked' => true,
            ]);
        }
    }

    /**
     * Get admin role permissions.
     *
     * @return array
     */
    private function getAdminRolePermissions()
    {
        return [
            // Users
            'private.users.index'   => true,
            'private.users.create'  => true,
            'private.users.edit'    => true,
            'private.users.destroy' => true,

            // Roles
            'private.roles.index'   => true,
            'private.roles.create'  => true,
            'private.roles.edit'    => true,
            'private.roles.destroy' => true,

            // Settings
            'private.settings.edit' => true,
        ];
    }
}
