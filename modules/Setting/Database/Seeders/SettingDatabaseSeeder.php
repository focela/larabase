<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Setting\Entities\Setting;

class SettingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Setting::exists()) {
            Setting::setMany([
                'supported_countries' => ['US'],
                'default_country'     => 'US',
                'supported_locales'   => ['en'],
                'default_locale'      => 'en',
                'default_timezone'    => 'UTC',
                'search_engine'       => 'pgsql',
            ]);
        }
    }
}
