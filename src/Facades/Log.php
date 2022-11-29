<?php

namespace Core\Facades;

/**
 * Class Log
 * @package Core\Facades
 * @mixin \Core\Log
 * @method  debug($data, $k = 0, $prefix = 'debug') static
 * @method  notice($data, $k = 0, $prefix = 'notice') static
 * @method  warn($data, $k = 0, $prefix = 'warn') static
 * @method  error($data, $k = 0, $prefix = 'error') static
 * @method  setTraceId($id) static
 * @method  getTraceId() static
 *
 */
class Log extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Core\Log::class;
    }
}