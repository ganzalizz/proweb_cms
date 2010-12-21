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
        $this->_module_sys_Name = $module_sys_name;
        
        $this->_module_config = new Ext_Common_Config($module_sys_name, 'config');
        
        $this->_moduleName = $this->_module_config->module->sys->name;
        $this->_module_tableName = $this->_module_config->module->table->name;
        
        $this->_db = Zend_Db_Table::getDefaultAdapter();        

        
    }
    
   
 
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
    
    protected function IsModuleRegistered()
    {
        $select = $this->_db->select()
                            ->from('site_divisions_type')
                            ->where('system_name = ?', $this->_module_sys_Name);
                           
        print_r($select);
        return ($this->_db->fetchRow($select))? true: false;
        
    }
    
    
    
    abstract public function Install(); 
    
   
    
    abstract public function Uninstall();
    
   
    
    
    
}
