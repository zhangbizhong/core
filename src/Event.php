<?php

namespace Core;

class Event
{
    /**
     * @var \Closure[][][]
     */
    private static $es = [];

    /**
     * @param string $class class name
     * @param string $event event name
     * @param \Closure $fn
     */
    public static function addListener($class, $event, $fn)
    {
        self::$es[$class][$event][] = $fn;
    }

    /**
     * @param string $class class name
     * @param string $event event name
     */
    public static function remove($class, $event = null)
    {
        if ($event) {
            unset(self::$es[$class][$event]);
        } else {
            unset(self::$es[$class]);
        }
    }

    /**
     * @param string $event event name
     * @param object $class dispatch object
     * @param array $args params
     * @param false $is_async true -> Run outside a process
     * @return bool
     */
    public static function dispatch($class, $event, $args = [], $is_async = false)
    {
        $c = get_class($class);
        if (!isset(self::$es[$c][$event])) {
            return false;
        }
        foreach (self::$es[$c][$event] as $val) {
            $val->call($class, ...$args);
        }
        return true;
    }
}