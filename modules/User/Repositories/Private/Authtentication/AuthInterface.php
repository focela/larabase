<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\User\Repositories\Private\Authtentication;

interface AuthInterface
{
    /**
     * Logs user into the system.
     *
     * @param string $account
     * @param string $password
     * @param bool   $remember
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login($account, $password, $remember = false);

    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user();
}
