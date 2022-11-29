<?php
/**
 * Created by PhpStorm.
 * User: tanszhe
 * Date: 2018/5/28
 * Time: 下午2:47
 */

namespace Core\Cache;

abstract class Cache
{
    abstract public function get($key, \Closure $closure = null, $ttl = 0, $tags = []);

    abstract public function delRegex($key);

    abstract public function flush($tag);

    abstract public function set($key, $val, $ttl = 0, $tags = []);

    abstract public function del($key);


}