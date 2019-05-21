<?php
// @codingStandardsIgnoreStart
ini_set('memory_limit', '512M');
ini_set('error_reporting', E_ALL);

$GLOBALS['ROOT_DIR'] = dirname(__FILE__) . '/../..';

/**
 * Loader for tests
 */
class Loader 
{
	/**
	 * Load method
	 *
	 * @param string $class
	 */
    public static function load($class)
    {
        if (preg_match('#^(Sendit\\\\Bliskapaczka)\b#', $class)) {
            $phpFile = $GLOBALS['ROOT_DIR'] . str_replace('Sendit/Bliskapaczka', '', str_replace('\\', '/', $class)) . '.php';
            require_once($phpFile);
        }
    }
}

spl_autoload_register(array(new Loader(), 'load'), true, true);
// @codingStandardsIgnoreEnd