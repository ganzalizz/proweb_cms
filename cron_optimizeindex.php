<?php
define('ROOT_DIR', (dirname(( __FILE__ ))) . DIRECTORY_SEPARATOR);
require_once(ROOT_DIR . 'application/system/Bootstrap.php');
Bootstrap::defineDirectoriesConstants();
$modules = Bootstrap::getAllModulesDirectories();
Bootstrap::setIncludePath(array_merge(array(
    '.',
    ROOT_DIR,
    DIR_LIBRARY,
    DIR_ZEND,
    DIR_PEAR,
    DIR_DEFAULT_CONTROLLERS,
    DIR_ADMIN_CONTROLLERS,
    DIR_COMMON,
    DIR_APPLICATION,
    DIR_ADMIN_MODELS,
    DIR_MODELS,
    DIR_MODULES

    ), $modules));
require_once(ROOT_DIR . 'application/library/Zend/Loader.php');
require_once(ROOT_DIR . 'application/library/Zend/Loader/Autoloader.php');
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);
Configurator::setupDatabase();

Search::optimizeindexes();
?>