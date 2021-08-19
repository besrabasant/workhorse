<?php

namespace Workhorse;

use Illuminate\Support\Arr;

class PluginRepository
{
    protected static $items = [];


    public static function has($key)
    {
        return Arr::has(static::$items, $key);
    }

    /**
     * Get the specified configuration value.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        if (is_array($key)) {
            return static::getMany($key);
        }

        return Arr::get(static::$items, $key, $default);
    }

    /**
     * Get many configuration values.
     *
     * @param  array  $keys
     * @return array
     */
    public static function getMany($keys)
    {
        $config = [];

        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                [$key, $default] = [$default, null];
            }

            $config[$key] = Arr::get(static::$items, $key, $default);
        }

        return $config;
    }

    /**
     * Add a given configuration value.
     *
     * @param  array|string  $key
     * @param  mixed  $value
     * @return void
     */
    public static function add($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            Arr::set(static::$items, $key, $value);
        }
    }

    /**
     * Prepend a value onto an array configuration value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public static function prepend($key, $value)
    {
        $array = static::get($key);

        array_unshift($array, $value);

        static::add($key, $array);
    }

    /**
     * Get all of the configuration items for the application.
     *
     * @return array
     */
    public static function all()
    {
        return static::$items;
    }
}
