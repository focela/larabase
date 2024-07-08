<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Support\Search;

use Laravel\Scout\Searchable as ScoutSearchable;

trait Searchable
{
    use ScoutSearchable {
        ScoutSearchable::search as scoutSearch;
    }

    /**
     * Perform a search against the model's indexed data.
     *
     * @param string  $query
     * @param Closure $callback
     *
     * @return Builder
     */
    public function search($query, $callback = null)
    {
        $scoutBuilder = $this->scoutSearch($query, $callback);

        return new Builder($this, $scoutBuilder);
    }
}
