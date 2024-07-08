<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Translation\Entities;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class TranslationTranslation extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['locale', 'value'];

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saved(function ($translationTranslation) {
            $translationTranslation->clearCache();
        });
    }

    /**
     * Clear translations cache.
     *
     * @return void
     */
    public static function clearCache()
    {
        Cache::tags('translations')->flush();
    }
}
