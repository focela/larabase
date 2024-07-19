<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;
use Focela\Laratrust\Laravel\Facades\Activation;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! User::exists()) {
            $adminRole = Role::first();

            $admin = User::create([
                'first_name' => 'System',
                'last_name'  => 'Administrator',
                'email'      => 'admin@example.com',
                'username'   => 'admin',
                'phone'      => '(650) 348-9988',
                'password'   => bcrypt('vkq3TAP*rtv@enj0zxd'),
            ]);

            $activation = Activation::create($admin);
            Activation::complete($admin, $activation->code);

            $adminRole->users()->attach($admin);
        }
    }
}
