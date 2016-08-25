<?php

namespace Automer;

class Timer
{
    private static $timers = array();

    public static function start($timer)
    {
        if (isset(static::$timers[$timer])) {
            throw new \Exception("The timer '$timer' is already being used.");
        }

        static::$timers[$timer] = microtime(true);
        return true;
    }

    public static function stop($timer)
    {
        if (!isset(static::$timers[$timer])) {
            throw new \Exception("The timer '$timer' hasn't been started.");
        }

        $time = microtime(true) - static::$timers[$timer];
        unset(static::$timers[$timer]);
        return $time;
    }
}
