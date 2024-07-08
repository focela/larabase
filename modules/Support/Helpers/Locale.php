<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Support\Helpers;

class Locale
{
    /**
     * Path of the resource.
     *
     * @var string
     */
    const RESOURCE_PATH = __DIR__;

    /**
     * Array of all locales.
     *
     * @var array
     */
    private static $locales;

    /**
     * Get all locale codes.
     *
     * @return array
     */
    public static function codes()
    {
        return array_keys(static::all());
    }

    /**
     * Get all locales.
     *
     * @return array
     */
    public static function all()
    {
        if (is_null(self::$locales)) {
            self::$locales = require dirname(self::RESOURCE_PATH, 1).'/Resources/locales.php';
        }

        return self::$locales;
    }

    /**
     * Get name of the given locale code.
     *
     * @param string $code
     *
     * @return string
     */
    public static function name($code)
    {
        return array_get(static::all(), $code);
    }
}
