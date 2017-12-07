<?php

/**
 * SPL class loader for Pheanstalk classes.
 *
 * @author Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
class Pheanstalk_ClassLoader
{
    const PACKAGE = 'Pheanstalk';

    private static $_path;

    /**
     * Registers Pheanstalk_ClassLoader as an SPL class loader.
     *
     * @param string $path Path to Pheanstalk classes directory
     */
    public static function register($path)
    {
        self::$_path = $path;
        self::addPath($path);

		spl_autoload_register(array(__CLASS__, 'load'));
    }

    /**
     * Attempts to load a Pheanstalk class file.
     *
     * @param string $class
     * @see http://php.net/manual/en/function.spl-autoload-register.php
     */
    public static function load($class)
    {
        if (substr($class, 0, strlen(self::PACKAGE)) != self::PACKAGE) {
            return false;
        }

        $path = sprintf(
            '%s/%s.php',
            self::$_path,
            str_replace('_', '/', $class)
        );

        if (file_exists($path)) {
            require_once($path);
        }
    }

    /**
     * @param mixed $items Path or paths as string or array
     */
    public static function addPath($items)
    {
        $elements = explode(PATH_SEPARATOR, get_include_path());

        set_include_path(implode(
            PATH_SEPARATOR,
            array_unique(array_merge((array)$items, $elements))
        ));
    }
}
