<?php

/**
 * Performs reading from app config files, and give
 * access to it
 *
 * Singleton class
 */
class Settings
{
    /**
     * Singleton instance member
     * @var Settings
     */
    protected static $_instance = null;
    
    /**
     * Zend_Config_Ini instance
     * @var Zend_Config_Ini
     */
    public $_config = null;
	
	
    /**
     * Singleton instance
     * @return  Settings
     */
    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    /**
     * Short access to Zend_Config_Ini
	 * @var Zend_Config_Ini
     */
    public static function getConfig(){
    	$_this = Settings::getInstance();
    	
    	if ($_this->_config === null) {
			$_this->_config = new Zend_Config_Ini(DIR_APPLICATION . 'configuration/configuration.ini', 'default');
    	}

    	return $_this->_config;
    }
}