<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Support\Search;

use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;
use Illuminate\Support\Facades\DB;

class MySqlSearchEngine extends Engine
{
    public function update($models)
    {
        // TODO: Implement update() method.
    }

    public function delete($models)
    {
        // TODO: Implement delete() method.
    }

    /**
     * Perform the given search on the engine.
     *
     * @param Builder $builder
     *
     * @return array
     */
    public function search(Builder $builder)
    {
        $result = [];

        $query = DB::table($builder->model->searchTable());

        $columns = implode(',', $builder->model->searchColumns());

        $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $this->getSearchKeyword($builder));

        if ($builder->callback) {
            $query = call_user_func($builder->callback, $query, $this);
        }

        $result['count'] = $query->count();

        if (property_exists($builder, 'orders') && ! empty($builder->orders)) {
            foreach ($builder->orders as $order) {
                $query->orderBy($order['column'], $order['direction']);
            }
        }

        if ($builder->limit) {
            $query = $query->take($builder->limit);
        }

        if (property_exists($builder, 'offset') && $builder->offset) {
            $query = $query->skip($builder->offset);
        }

        $result['results'] = $query->pluck($builder->model->searchKey());

        return $result;
    }

    /**
     * Perform the given search on the engine.
     *
     * @param Builder $builder
     * @param int     $perPage
     * @param int     $page
     *
     * @return array
     */
    public function paginate(Builder $builder, $perPage, $page)
    {
        $builder->limit  = $perPage;
        $builder->offset = ($perPage * $page) - $perPage;

        return $this->search($builder);
    }

    /**
     * Pluck and return the primary keys of the given results.
     *
     * @param mixed $results
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapIds($results)
    {
        return $results['results'];
    }

    /**
     * Map the given results to instances of the given model.
     *
     * @param Builder                             $builder
     * @param mixed                               $results
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function map(Builder $builder, $results, $model)
    {
        return $results['results'];
    }

    public function lazyMap(Builder $builder, $results, $model)
    {
        // TODO: Implement lazyMap() method.
    }

    /**
     * Get the total count from a raw result returned by the engine.
     *
     * @param mixed $results
     *
     * @return int
     */
    public function getTotalCount($results)
    {
        return $results['count'];
    }

    public function flush($model)
    {
        // TODO: Implement flush() method.
    }

    public function createIndex($name, array $options = [])
    {
        // TODO: Implement createIndex() method.
    }

    public function deleteIndex($name)
    {
        // TODO: Implement deleteIndex() method.
    }
}
