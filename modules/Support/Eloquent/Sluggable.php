<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Support;

namespace Modules\Support\Eloquent;

use Illuminate\Database\Eloquent\SoftDeletes;

trait Sluggable
{
    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootSluggable()
    {
        static::creating(function ($entity) {
            $entity->setSlug();
        });
    }

    /**
     * Set the slug attribute.
     *
     * @param string $value
     *
     * @return void
     */
    public function setSlug($value = null)
    {
        if (is_null($value)) {
            $value = $this->getAttribute($this->slugAttribute);
        }

        $this->attributes['slug'] = $this->generateSlug($value);
    }

    /**
     * Generate slug by the given value.
     *
     * @param string $value
     *
     * @return string
     */
    private function generateSlug($value)
    {
        $slug = str_slug($value) ?: slugify($value);

        $query = $this->where('slug', $slug)->withoutGlobalScope('active');

        if (array_has(class_uses($this), SoftDeletes::class)) {
            $query->withTrashed();
        }

        if ($query->exists()) {
            $slug .= '-'.str_random(8);
        }

        return $slug;
    }
}
