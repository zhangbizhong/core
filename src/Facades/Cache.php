<?php

namespace Core\Facades;


use Core\Cache\File;
use Core\Cache\Redis;

/**
 * Class Cache
 * @package Facades
 * @mixin \Core\Cache\Redis
 * @mixin \Redis
 * @method string get($key, \Closure $closure = null, $ttl = 0, $tags = []) static
 * @method bool delRegex($key) static
 * @method bool del($key) static
 * @method bool flush($tag) static
 * @method bool set($key, $val, $ttl = 0, $tags = []) static
 * @method Redis setConnection($key)
 */
class Cache extends Facade
{
    protected static function getFacadeAccessor()
    {
        switch (config('cache.drive')) {
            case 'file':
                return File::class;
                break;
            case 'redis':
                return Redis::class;
                break;
            default:
                exit('no cache drive');
        }
    }
}
