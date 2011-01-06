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
                           
       
        return ($this->_db->fetchRow($select))? true: false;
        
    }
    
    
    
    abstract public function Install(); 
    
   
    
    abstract public function Uninstall();
    /**
     * 
     */
    protected function DoRoute()
    {
      
      $route_config = new Zend_Config_Yaml($this->_module_config->main->config->path.'routes.yml',null,
                              array('skipExtends'        => true,
                                    'allowModifications' => true));
                $route_name = $this->_module_sys_Name.'item';
     		$route_config->routes->routes->$route_name = array();
                $route_config->routes->routes->$route_name->__set('type', "Zend_Controller_Router_Route" );
                $route_config->routes->routes->$route_name->__set('route', $route_name."/*");
                $route_config->routes->routes->$route_name->defaults = array();
                $route_config->routes->routes->$route_name->defaults->__set('module', $this->_module_sys_Name);                
                $route_config->routes->routes->$route_name->defaults->__set('controller', $this->_module_sys_Name);
                $route_config->routes->routes->$route_name->defaults->__set('action',$this->_module_sys_Name.'item');
                //$route_config->routes->routes->$route_name->defaults->__set('id', $data['id']);
                
                
                $writer = new Zend_Config_Writer_Yaml();
                $writer->setFilename($this->_module_config->main->config->path.'routes.yml');
                $writer->setConfig($route_config);
                $writer->write();
		
     }
     /**
      * 
      */
     protected function DeleteRoute()
     {
       $route_config = new Zend_Config_Yaml($this->_module_config->main->config->path.'routes.yml',null,
                              array('skipExtends'        => true,
                                    'allowModifications' => true));
       $route_config->routes->routes->__unset($this->_module_sys_Name.'item');
       
       $writer = new Zend_Config_Writer_Yaml();
                $writer->setFilename($this->_module_config->main->config->path.'routes.yml');
                $writer->setConfig($route_config);
                $writer->write();
       
       
     }
    
   
    
    
    
}
