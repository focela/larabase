<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Support\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;

class EloquentMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('resetOrders', function () {
            $this->{$this->unions ? 'unionOrders' : 'orders'} = null;

            return $this;
        });
    }
}
