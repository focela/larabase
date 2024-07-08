<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Core\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     * @param string   $permission
     *
     * @return JsonResponse
     */
    public function handle(Request $request, \Closure $next, $permission)
    {
        if (! auth()->user()->hasAccess($permission)) {
            return $this->handleUnauthorizedRequest($permission);
        }

        return $next($request);
    }

    /**
     * Handle unauthorized request.
     *
     * @param string $permission
     *
     * @return JsonResponse
     */
    private function handleUnauthorizedRequest($permission)
    {
        return response()->json([
            'message' => __('core::validation.permission_denied', ['permission' => $permission]),
            'code'    => 401,
        ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
    }
}
