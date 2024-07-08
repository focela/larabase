<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Support\Helpers;

class TimeZone
{
    /**
     * Array of all time zones.
     *
     * @var array
     */
    private static $timeZones;

    /**
     * Get all defined time zones.
     *
     * @return array
     */
    public static function all()
    {
        if (! is_null(self::$timeZones)) {
            return self::$timeZones;
        }

        $timeZones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);

        return self::$timeZones = array_combine($timeZones, $timeZones);
    }
}
