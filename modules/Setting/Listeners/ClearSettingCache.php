<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Setting\Listeners;

use Illuminate\Support\Facades\Cache;

class ClearSettingCache
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        foreach (supported_locale_keys() as $locale) {
            Cache::forget(md5('settings.all:'.$locale));
        }
    }
}
