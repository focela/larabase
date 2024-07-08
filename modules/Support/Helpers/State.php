<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Support\Helpers;

class State
{
    /**
     * Path of the resource.
     *
     * @var string
     */
    const RESOURCE_PATH = __DIR__;

    /**
     * Array of all states.
     *
     * @var array
     */
    private static $states;

    /**
     * Get name of the given locale code.
     *
     * @param string $countryCode
     * @param string $stateCode
     *
     * @return string
     */
    public static function name($countryCode, $stateCode)
    {
        return array_get(self::get($countryCode), $stateCode);
    }

    /**
     * Get all states of the given country code.
     *
     * @param string $code
     *
     * @return array|null
     */
    public static function get($code)
    {
        if (isset(self::$states[$code])) {
            return self::$states[$code];
        }

        $path = dirname(self::RESOURCE_PATH, 1)."/Resources/states/{$code}.php";

        if (file_exists($path)) {
            return self::$states[$code] = require $path;
        }

        return null;
    }
}
