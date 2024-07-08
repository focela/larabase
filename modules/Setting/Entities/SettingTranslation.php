<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Setting\Entities;

use Modules\Support\Eloquent\TranslationModel;

class SettingTranslation extends TranslationModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['value'];

    /**
     * Get the value of the setting.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        return unserialize($value);
    }

    /**
     * Set the value of the setting.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = serialize($value);
    }
}
