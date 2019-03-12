<?php

namespace Dolondro\GoogleAuthenticator\Tests\Helper;

use Psr\SimpleCache\CacheInterface;

/**
 * Class ArrayPsr16
 * A very half arsed incorrect implementation as it's really just to prove a point.
 */
class ArrayPsr16Cache implements CacheInterface
{
    protected $data = [];

    public function get($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    public function set($key, $value, $ttl = null)
    {
        $this->data[$key] = $value;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function delete($key)
    {
        // TODO: Implement delete() method.
    }

    public function clear()
    {
        // TODO: Implement clear() method.
    }

    public function getMultiple($keys, $default = null)
    {
        // TODO: Implement getMultiple() method.
    }

    public function setMultiple($values, $ttl = null)
    {
        // TODO: Implement setMultiple() method.
    }

    public function deleteMultiple($keys)
    {
        // TODO: Implement deleteMultiple() method.
    }
}
