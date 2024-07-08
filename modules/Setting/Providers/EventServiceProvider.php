<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Setting\Providers;

use Modules\Setting\Events\SettingSaved;
use Modules\Setting\Listeners\ClearSettingCache;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SettingSaved::class => [
            ClearSettingCache::class,
        ],
    ];
}
