<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\User\Http\Controllers\Private;

use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Private\LoginRequest;
use Modules\User\Repositories\Private\Authtentication\AuthInterface;

class AuthController extends Controller
{
    /**
     * The authentication instance.
     *
     * @var AuthInterface
     */
    private $auth;

    /**
     * Constructor.
     *
     * @param AuthInterface $auth
     *
     * @return void
     */
    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Logs user into the system.
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogin(LoginRequest $request)
    {
        return $this->auth->login($request->get('account'), $request->get('password'), (bool) $request->get('remember_me', false));
    }

    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser()
    {
        return $this->auth->user();
    }
}
