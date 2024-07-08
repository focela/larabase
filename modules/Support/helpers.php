<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

if (! function_exists('boolean_convert')) {
    /**
     * Convert boolean value.
     *
     * @param $value
     *
     * @return bool
     */
    function boolean_convert($value)
    {
        if ($value === '1') {
            return true;
        }

        if ($value === '-1') {
            return false;
        }

        return $value;
    }
}

if (! function_exists('locale')) {
    /**
     * Get current locale.
     *
     * @return string
     */
    function locale()
    {
        return app()->getLocale();
    }
}

if (! function_exists('currency')) {
    /**
     * Get current currency.
     *
     * @throws Psr\Container\NotFoundExceptionInterface
     * @throws Psr\Container\ContainerExceptionInterface
     *
     * @return string
     */
    function currency()
    {
        $currency = Cookie::get('currency');

        if (! in_array($currency, setting('supported_currencies'))) {
            $currency = setting('default_currency');
        }

        return $currency;
    }
}

if (! function_exists('supported_locales')) {
    /**
     * Get all supported locales.
     *
     * @return array
     */
    function supported_locales()
    {
        return LaravelLocalization::getSupportedLocales();
    }
}

if (! function_exists('supported_locale_keys')) {
    /**
     * Get all supported locale keys.
     *
     * @return array
     */
    function supported_locale_keys()
    {
        return LaravelLocalization::getSupportedLanguagesKeys();
    }
}

if (! function_exists('is_multilingual')) {
    /**
     * Determine if the app has multi-language.
     *
     * @return bool
     */
    function is_multilingual()
    {
        return count(supported_locales()) > 1;
    }
}

if (! function_exists('is_module_enabled')) {
    /**
     * Determine if the given module is enabled.
     *
     * @param string $module
     *
     * @return bool
     */
    function is_module_enabled($module)
    {
        return array_key_exists($module, app('modules')->allEnabled());
    }
}

if (! function_exists('slugify')) {
    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param string $value
     */
    function slugify($value)
    {
        $slug = preg_replace('/[\s<>[\]{}|\\^%&\$,\/:;=?@#\'\"]/', '-', mb_strtolower($value));

        // Remove duplicate separators.
        $slug = preg_replace('/-+/', '-', $slug);

        // Trim special characters from the beginning and end of the slug.
        return trim($slug, '!"#$%&\'()*+,-./:;<=>?@[]^_`{|}~');
    }
}

if (! function_exists('array_reset_index')) {
    /**
     * Reset numeric index of an array recursively.
     *
     * @param array $array
     *
     * @return array
     *
     * @see https://stackoverflow.com/a/12399408/5736257
     */
    function array_reset_index($array)
    {
        $array = $array instanceof Collection
            ? $array->toArray()
            : $array;

        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $array[$key] = array_reset_index($val);
            }
        }

        if (isset($key) && is_numeric($key)) {
            return array_values($array);
        }

        return $array;
    }
}
