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
                'first_name' => 'Administrator',
                'last_name'  => 'Mr.',
                'email'      => 'admin@example.com',
                'username'   => 'admin',
                'phone'      => '518-528-5410',
                'password'   => bcrypt('123123'),
            ]);

            $activation = Activation::create($admin);
            Activation::complete($admin, $activation->code);

            $adminRole->users()->attach($admin);
        }
    }
}
