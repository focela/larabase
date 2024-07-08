<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Support\Providers;

use Laravel\Scout\EngineManager;
use Illuminate\Support\ServiceProvider;
use Modules\Support\Search\MySqlSearchEngine;

class MySQLScoutServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app[EngineManager::class]->extend('mysql', function () {
            return new MySqlSearchEngine();
        });
    }
}
