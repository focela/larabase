<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Support\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Astrotomic\Translatable\Translatable as AstrotomicTranslatable;

trait Translatable
{
    use AstrotomicTranslatable;

    /**
     * Save the model to the database.
     *
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        if (parent::save($options)) {
            return $this->saveTranslations();
        }

        return false;
    }

    /**
     * This scope filters results by checking the translation fields.
     *
     * @param Builder $query
     * @param string  $key
     * @param array   $values
     * @param string  $locale
     *
     * @return Builder
     */
    public function scopeWhereTranslationIn($query, $key, array $values, $locale = null)
    {
        return $query->whereHas('translations', function ($query) use ($key, $values, $locale) {
            $query->whereIn($key, $values)
                ->when(! is_null($locale), function ($query) use ($locale) {
                    $query->where('locale', $locale);
                })
            ;
        });
    }
}
