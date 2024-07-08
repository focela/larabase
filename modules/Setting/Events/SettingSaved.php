<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Setting\Events;

use Modules\Setting\Entities\Setting;
use Illuminate\Queue\SerializesModels;

class SettingSaved
{
    use SerializesModels;

    /**
     * The setting model.
     *
     * @var Setting
     */
    public $setting;

    /**
     * Create a new event instance.
     *
     * @param Setting $setting
     *
     * @return void
     */
    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }
}
