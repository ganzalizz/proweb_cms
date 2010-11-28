<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
abstract class InstallModuleAbstract 
{
    /**
     *
     * @var InstallModuleAbstract 
     */
    private static $instance;
    /**
     *
     * @var _db - Zend_Db_Adapter_Pdo_Mysql
     */
    protected $_db = null;
    
    /**
     *
     * @var _module_config - Zend_Config_Ini  
     */
    protected $_module_config = null;
    
    /**
     * 
     */
    private function  __construct() 
    {
        $this->_news_config = new Ext_Common_Config($this->_moduleName, 'config');
        
        $config_db = new Zend_Config_Ini($this->_news_config->main->config->path.'configuration.ini', 'db');
                
        $this->_db = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host' => $config_db->db->config->host,
            'username' => $config_db->db->config->username,
            'password' => $config_db->db->config->password,
            'dbname' => $config_db->db->config->dbname,
            'charset' => 'utf8'));
    }
    /**
     *
     * @return InstallModuleAbstract 
     */
    public static function getInstance()
    {

        if ($instance === null)
        {
            self::$instance = new News_Admin_NewsInstall();
        }
        return self::$instance;

    }
    /**
     * 
     */
    public final function __clone()
    {
        trigger_error( "Cannot clone instance of Singleton pattern", E_USER_ERROR );
    }
    
    abstract public function Install();
   
    
    abstract public function Uninstall();
   
    
     public function  __destruct() {
        
        $this->_db->closeConnection();
    }
}
