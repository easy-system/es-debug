<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\Debug;

/**
 * Provides debugging a variables.
 */
class Debug
{
    /**
     * The array of dump instances.
     *
     * @var array
     */
    protected static $instances = [];

    /**
     * Creates a dump of the variable.
     *
     * @param mixed $__variable The variable for debugging
     *
     * @return Dump The instance of dump
     */
    public static function dump($__variable)
    {
        ob_start();
        var_dump($__variable);

        return static::registerDump(
            ob_get_clean(),
            debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)
        );
    }

    /**
     * Gets the array of dump instances.
     *
     * @return array The array of dump instances
     */
    public static function getDumpInstances()
    {
        return static::$instances;
    }

    /**
     * Registers a dump.
     *
     * @param string $representation The string representation of dump
     * @param array  $backtrace      The backtrace
     *
     * @return Dump The instance of dump
     */
    protected static function registerDump($representation, $backtrace)
    {
        $dump = new Dump($representation, $backtrace[0]['file'], $backtrace[0]['line']);

        static::$instances[] = $dump;

        return $dump;
    }
}
