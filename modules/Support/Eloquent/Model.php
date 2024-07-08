<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Support\Eloquent;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{
    use HasUuids;

    /**
     * The relations to eager load on without query.
     *
     * @return Builder
     */
    public static function queryWithoutEagerRelations()
    {
        return (new static())->newQueryWithoutEagerRelations();
    }

    /**
     * The relations to eager load on without new query.
     *
     * @return Builder
     */
    public function newQueryWithoutEagerRelations()
    {
        return $this->registerGlobalScopes($this->newModelQuery()->withCount($this->withCount));
    }

    /**
     * Register a new active global scope on the model.
     *
     * @return void
     */
    public static function addActiveGlobalScope()
    {
        static::addGlobalScope('active', function ($query) {
            $query->where('is_active', true);
        });
    }

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booting()
    {
        static::saved(function ($entity) {
            $entity->clearEntityTaggedCache();
        });

        static::deleted(function ($entity) {
            $entity->clearEntityTaggedCache();
        });
    }

    /**
     * Clear entity tagged cache.
     *
     * @return void
     */
    public function clearEntityTaggedCache()
    {
        Cache::tags($this->getTable())->flush();
    }
}
