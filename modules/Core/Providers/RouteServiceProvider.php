<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapModuleRoutes();
    }

    /**
     * Map routes from all enabled modules.
     *
     * @return void
     */
    private function mapModuleRoutes()
    {
        foreach ($this->app['modules']->allEnabled() as $module) {
            $this->groupRoutes("Modules\\{$module->getName()}\\Http\\Controllers", function () use ($module) {
                $this->mapPrivateRoutes("{$module->getPath()}/Routes/private.php");
                $this->mapPublicRoutes("{$module->getPath()}/Routes/public.php");
            });
        }
    }

    /**
     * Group routes to common prefix and middleware.
     *
     * @param string   $namespace
     * @param \Closure $callback
     *
     * @return void
     */
    private function groupRoutes($namespace, $callback)
    {
        Route::group([
            'namespace'  => $namespace,
            'prefix'     => LaravelLocalization::setLocale(),
            'middleware' => ['localize', 'locale_session_redirect', 'localization_redirect'],
        ], function () use ($callback) {
            $callback();
        });
    }

    /**
     * Map admin routes.
     *
     * @param mixed $path
     *
     * @return void
     */
    private function mapPrivateRoutes($path)
    {
        if (! file_exists($path)) {
            return;
        }

        Route::group([
            'namespace'  => 'Private',
            'prefix'     => 'api/private',
            'middleware' => ['auth'],
        ], function () use ($path) {
            require_once $path;
        });
    }

    /**
     * Map public routes.
     *
     * @param mixed $path
     *
     * @return void
     */
    private function mapPublicRoutes($path)
    {
        if (! file_exists($path)) {
            return;
        }

        Route::group([
            'prefix' => 'api',
        ], function () use ($path) {
            require_once $path;
        });
    }
}
