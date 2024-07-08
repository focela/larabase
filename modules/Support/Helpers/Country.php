<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Support\Helpers;

class Country
{
    /**
     * Path of the resource.
     *
     * @var string
     */
    const RESOURCE_PATH = __DIR__;

    /**
     * Array of all countries.
     *
     * @var array
     */
    private static $countries;

    /**
     * Array of supported countries by the app.
     *
     * @var array
     */
    private static $supported;

    /**
     * Get all supported countries.
     *
     * @return array
     */
    public static function supported()
    {
        if (! is_null(self::$supported)) {
            return self::$supported;
        }

        $supportedCountries = setting('supported_countries');

        return self::$supported = array_filter(static::all(), function ($code) use ($supportedCountries) {
            return in_array($code, $supportedCountries);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get all countries.
     *
     * @return array
     */
    public static function all()
    {
        if (is_null(self::$countries)) {
            self::$countries = require dirname(self::RESOURCE_PATH, 1).'/Resources/countries.php';
        }

        return self::$countries;
    }

    /**
     * Get all country codes.
     *
     * @return array
     */
    public static function codes()
    {
        return array_keys(self::all());
    }
}
