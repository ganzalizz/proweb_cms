<?php
define('ROOT_DIR', (dirname(( __FILE__ ))) . DIRECTORY_SEPARATOR);

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configuration/configuration.ini'
);

$application->bootstrap()
            ->run();


include_once 'start.inc.php';

//require_once(ROOT_DIR . 'application/system/Loader.php');
//Loader::load('application/system/Bootstrap');
//Bootstrap::start();
//$application->bootstrap('ZFDebug');
//$application->bootstrap()
//            ->run();
include_once 'end.inc.php';
