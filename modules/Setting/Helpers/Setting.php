<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Setting\Helpers;

use Illuminate\Support\Collection;

class Setting implements \ArrayAccess
{
    /**
     * Collection of all settings.
     *
     * @var Collection
     */
    private $settings;

    /**
     * Create a new repository instance.
     *
     * @param Collection $settings
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    /**
     * Get all settings.
     *
     * @return array
     */
    public function all()
    {
        return $this->settings->all();
    }

    /**
     * Determine if a setting is existing.
     *
     * @param string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->settings->has($offset);
    }

    /**
     * Unset a setting by the given key.
     *
     * @param string $offset
     *
     * @return Collection
     */
    public function offsetUnset($offset)
    {
        return $this->settings->forget($offset);
    }

    /**
     * Get setting for the given key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * Set a key / value setting pair.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * Get setting for the given key.
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Get setting for the given key.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->settings->get($key) ?: $default;
    }

    /**
     * Set a key / value setting pair.
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->set([$offset => $value]);
    }

    /**
     * Set the given settings.
     *
     * @param array $settings
     *
     * @return void
     */
    public function set($settings = [])
    {
        \Modules\Setting\Entities\Setting::setMany($settings);
    }
}
