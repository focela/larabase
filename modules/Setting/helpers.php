<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

if (! function_exists('setting')) {
    /**
     * Get / set the specified setting value.
     * If an array is passed, we'll assume you want to set settings.
     *
     * @param null  $key
     * @param mixed $default
     *
     * @throws Psr\Container\NotFoundExceptionInterface
     * @throws Psr\Container\ContainerExceptionInterface
     *
     * @return Modules\Setting\Helpers\Setting
     */
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('setting');
        }

        if (is_array($key)) {
            return app('setting')->set($key);
        }

        try {
            return app('setting')->get($key, $default);
        } catch (PDOException $e) {
            return $default;
        }
    }
}
