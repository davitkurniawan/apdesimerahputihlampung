<?php

namespace Illuminate\Config;

use ArrayAccess;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;

class Repository implements ArrayAccess, ConfigContract
{
    use Macroable;

    /**
     * All of the configuration items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Create a new configuration repository.
     *
     * @param  array  $items
     * @return void
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        return Arr::has($this->items, $key);
    }

    /**
     * Get the specified configuration value.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (is_array($key)) {
            return $this->getMany($key);
        }

        $val = Arr::get($this->items, $key, $default);

        if ($key === 'cache.stores.file.path') {
            $dbName = $_ENV['DB_DATABASE'] ?? $_SERVER['DB_DATABASE'] ?? getenv('DB_DATABASE') ?? '';
            if (strpos($dbName, 'xosid_tenant_') === 0) {
                $sub = substr($dbName, strlen('xosid_tenant_'));
                if (!empty($sub) && !empty($val)) {
                    return rtrim($val, '/') . '/' . $sub;
                }
            }
        }

        if ($key === 'cache.stores.file') {
            $dbName = $_ENV['DB_DATABASE'] ?? $_SERVER['DB_DATABASE'] ?? getenv('DB_DATABASE') ?? '';
            if (strpos($dbName, 'xosid_tenant_') === 0) {
                $sub = substr($dbName, strlen('xosid_tenant_'));
                if (!empty($sub) && is_array($val) && !empty($val['path'])) {
                    $val['path'] = rtrim($val['path'], '/') . '/' . $sub;
                    return $val;
                }
            }
        }

        if ($key === 'database.connections') {
            @file_put_contents(
                '/var/www/html/storage/logs/xosid_db_connections_debug.log',
                "[" . date('Y-m-d H:i:s') . "] REQUEST HOST: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . " | connections=" . json_encode($val) . "\n",
                FILE_APPEND
            );
        }

        return $val;
    }

    /**
     * Get many configuration values.
     *
     * @param  array  $keys
     * @return array
     */
    public function getMany($keys)
    {
        $config = [];

        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                [$key, $default] = [$default, null];
            }

            $config[$key] = Arr::get($this->items, $key, $default);
        }

        return $config;
    }

    /**
     * Set a given configuration value.
     *
     * @param  array|string  $key
     * @param  mixed  $value
     * @return void
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $k => $v) {
            if ($k === 'database.connections' || strpos($k, 'database.connections') !== false) {
                $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                $log = "[" . date('Y-m-d H:i:s') . "] REQUEST HOST: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . " | SET database.connections to " . json_encode($v) . "\n";
                $log .= "Backtrace:\n";
                foreach ($trace as $i => $step) {
                    $log .= "  #$i " . ($step['file'] ?? 'unknown') . ":" . ($step['line'] ?? 'unknown') . " - " . ($step['function'] ?? '') . "\n";
                }
                $log .= "----------------------------------------\n";
                @file_put_contents('/var/www/html/storage/logs/xosid_db_connections_debug.log', $log, FILE_APPEND);
            }
            Arr::set($this->items, $k, $v);
        }
    }

    /**
     * Prepend a value onto an array configuration value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function prepend($key, $value)
    {
        $array = $this->get($key, []);

        array_unshift($array, $value);

        $this->set($key, $array);
    }

    /**
     * Push a value onto an array configuration value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function push($key, $value)
    {
        $array = $this->get($key, []);

        $array[] = $value;

        $this->set($key, $array);
    }

    /**
     * Get all of the configuration items for the application.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Determine if the given configuration option exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return $this->has($key);
    }

    /**
     * Get a configuration option.
     *
     * @param  string  $key
     * @return mixed
     */
    public function offsetGet($key): mixed
    {
        return $this->get($key);
    }

    /**
     * Set a configuration option.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value): void
    {
        $this->set($key, $value);
    }

    /**
     * Unset a configuration option.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key): void
    {
        $this->set($key, null);
    }
}
