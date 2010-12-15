<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


require_once APPLICATION_PATH.'/library/Ext/Common/InstallModuleAbstract.php';

class TemplatesInstall extends Ext_Common_InstallModuleAbstract
{
     public function Install()
    {
       
        
        $this->RegisterModule();
        $this->DoRoute();
        
        
    }
    
    public function Uninstall()
    {
        
        $this->UnregisteredModule();
        $this->DeleteRoute();
       
    }
    
    private function RegisterModule()
    {
        $create_table = "CREATE TABLE IF NOT EXISTS ".$this->_module_tableName."(
                                          id int(11) unsigned NOT NULL AUTO_INCREMENT,
                                          title varchar(255) NOT NULL,
                                          describe text NOT NULL,
                                          price int(11) unsigned NOT NULL DEFAULT '100',
                                          template_image varchar(255) NOT NULL,
                                          is_active tinyint(1) NOT NULL DEFAULT '1',
                                          count_views int(11) unsigned NOT NULL DEFAULT '0',
                                          seo_title varchar(150) NOT NULL,
                                          seo_descriptions varchar(300) NOT NULL,
                                          seo_keywords varchar(500) NOT NULL,
                                          PRIMARY KEY (id))
                                          ENGINE=InnoDB
                                          DEFAULT CHARSET=utf8
                                          COLLATE = utf8_general_ci;";
            
          $register_module_sql = "
          INSERT INTO site_divisions_type(system_name,
                                          title,
                                          module,
                                          controller_frontend,
                                          action_frontend,
                                          controller_backend,
                                          action_backend,
                                          priority,
                                          active,
                                          go_to_module)
                    VALUES('templates',
                           'Шаблоны сайтов',
                           'templates',
                           'templates',
                           'index',
                           'admin_tempates',
                           'index',
                           0,1,1);";
               
        $this->_db->beginTransaction();
        
        $this->_db->getConnection()->exec($create_table);
        
        if (!$this->IsModuleRegistered())
                $this->_db->getConnection()->exec($register_module_sql);
        
                $where = $this->_db-> quoteInto('name = ?', 'templates');
                $this->_db->update('site_modules', array('installed' => 1), $where);
        $this->_db->commit();
    }
    
    protected function UnregisteredModule()
    {
        $delete_table = "DROP TABLE IF EXISTS ".$this->_module_tableName;
        $this->_db->beginTransaction();
        
        $this->_db->exec($delete_table);
        
        $where = $this->_db->quoteInto('module = ?', 'templates');
        $this->_db->delete('site_divisions_type', $where);
        $where = $this->_db->quoteInto('name = ?', 'templates');
        $this->_db->update('site_modules', array('installed' => 0), $where);
        
        $this->_db->commit();
    }
    
    private function DoRoute()
    {
      
      $route_config = new Zend_Config_Yaml($this->_module_config->main->config->path.'routes.yml',null,
                              array('skipExtends'        => true,
                                    'allowModifications' => true));
                $route_name = $this->_module_sys_Name.'item';
     		$route_config->routes->routes->$route_name = array();
                $route_config->routes->routes->$route_name->__set('type', "Zend_Controller_Router_Route" );
                $route_config->routes->routes->$route_name->__set('route', $route_name);
                $route_config->routes->routes->$route_name->defaults = array();
                $route_config->routes->routes->$route_name->defaults->__set('module', $this->_module_sys_Name);                
                $route_config->routes->routes->$route_name->defaults->__set('controller', $this->_module_sys_Name);
                $route_config->routes->routes->$route_name->defaults->__set('action',$this->_module_sys_Name.'item');
                $route_config->routes->routes->$route_name->defaults->__set('id', $data['id']); 
                
                
                $writer = new Zend_Config_Writer_Yaml();
                $writer->setFilename($this->_module_config->main->config->path.'routes.yml');
                $writer->setConfig($route_config);
                $writer->write();
		
     }
     
     private function DeleteRoute()
     {
       $route_config = new Zend_Config_Yaml($this->_module_config->main->config->path.'routes.yml',null,
                              array('skipExtends'        => true,
                                    'allowModifications' => true));
       $route_config->__unset($this->_module_sys_Name.'item');
       
       $writer = new Zend_Config_Writer_Yaml();
                $writer->setFilename($this->_module_config->main->config->path.'routes.yml');
                $writer->setConfig($route_config);
                $writer->write();
       
       
     }
}
