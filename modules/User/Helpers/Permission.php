<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\User\Helpers;

use Nwidart\Modules\Facades\Module;

class Permission
{
    /**
     * Get the permissions from all the enabled modules.
     *
     * @return array
     */
    public static function all()
    {
        return static::getEnabledModulePermissions();
    }

    /**
     * Get enabled module permissions.
     *
     * @return array
     */
    private static function getEnabledModulePermissions()
    {
        $permissions = [];

        foreach (Module::allEnabled() as $module) {
            $config = config('app.modules.'.strtolower($module->getName()).'.permissions');

            if (! is_null($config)) {
                $permissions[$module->getName()] = $config;
            }
        }

        return $permissions;
    }

    /**
     * Prepare given permissions.
     *
     * @param array $permissions
     *
     * @return string
     */
    public static function prepare(array $permissions)
    {
        $preparedPermissions = [];

        foreach ($permissions as $name => $value) {
            if (is_null($value) || is_bool($value)) {
                $preparedPermissions[$name] = $value;

                continue;
            }

            if (! is_null(static::value($value))) {
                $preparedPermissions[$name] = static::value($value);
            }
        }

        return json_encode($preparedPermissions);
    }

    /**
     * Get the permission value.
     *
     * @param $permission
     *
     * @return bool
     */
    protected static function value($permission)
    {
        if ($permission === '1') {
            return true;
        }

        if ($permission === '-1') {
            return false;
        }
    }
}
