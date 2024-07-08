<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

if (! function_exists('permission_value')) {
    /**
     * Get the integer representation value of the permission.
     *
     * @param array  $permissions
     * @param string $permission
     *
     * @return int
     */
    function permission_value(array $permissions, $permission)
    {
        $value = array_get($permissions, $permission);

        if (is_null($value)) {
            return 0;
        }

        if ($value) {
            return 1;
        }

        if (! $value) {
            return -1;
        }
    }
}
