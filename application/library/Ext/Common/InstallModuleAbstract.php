<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
abstract class Ext_Common_InstallModuleAbstract 
{
    /**
     *
     * @var InstallModuleAbstract 
     */
   // protected static $_instance;
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
     * @var string 
     */
    protected $_module_sys_Name = null;
    /**
     *
     * @var string
     */
    protected $_module_tableName = null;
    
   
     /**
     *
     * @param string $module_sys_name 
     */
    public function  __construct($module_sys_name) 
    {
        $this->_module_config = new Ext_Common_Config($module_sys_name, 'config');
        echo "Module name: $module_sys_name";
        $this->_moduleName = $this->_module_config->module->sys->name;
        $this->_module_tableName = $this->_module_config->module->table->name;
        
        $config_db = new Zend_Config_Ini($this->_module_config->main->config->path.'configuration.ini', 'db');
                
        $this->_db = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host' => $config_db->db->config->host,
            'username' => $config_db->db->config->username,
            'password' => $config_db->db->config->password,
            'dbname' => $config_db->db->config->dbname,
            'charset' => 'utf8'));
        echo "Initialize Database";
    }
    
   
    
//    public static function getInstance($module_sys_name)
//   {   echo "Instance";
//
//        if (self::$_instance === null)
//        {
//            self::$_instance = new self($module_sys_name);
//        }
//        return self::$_instance;
//
//   }
   /**
    * 
    */
//    private final function __clone()
//    {
//        trigger_error( "Cannot clone instance of Singleton pattern", E_USER_ERROR );
//    }
//   
   /**
    *
    * @return Zend_Config_Ini
    */
    public static function getModuleConfig()
    {
        return $this->_module_config;
    }
    
     public function  __destruct() {
        
        $this->_db->closeConnection();
    }
    
    abstract public function Install(); 
    
   
    
    abstract public function Uninstall();
    
   
    
    
    
}
