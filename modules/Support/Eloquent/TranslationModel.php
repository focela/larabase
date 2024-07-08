<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Support\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TranslationModel extends Model
{
    use HasUuids;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    public static function booting()
    {
        static::addGlobalScope('locale', function ($query) {
            $query->whereIn('locale', [locale(), config('app.fallback_locale')]);
        });
    }
}
