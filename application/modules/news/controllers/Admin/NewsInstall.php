<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'ModuleInstall.php';

class News_Admin_NewsInstall extends InstallModuleAbstract
{
    
  
        
    protected $_moduleName = "news";
    
    protected $_tableName = 'site_news';
    
    //TODO: Сделать префикс для названия таблиц
    protected $_ins_id = null;
    
    
    public function Install()
    {
        
        $this->RegisterModule();
        $this->DoRoute();
    }
    
    public function Uninstall()
    {
        $this->UnregisteredModule();
        $this->ClearRoute();
    }
    
    
    
    protected function RegisterModule()
    {
        
        
       
       
        $sql = "CREATE TABLE IF NOT EXISTS ".$this->_tableName." (
                     id int(11) unsigned NOT NULL AUTO_INCREMENT,
                     name varchar(255) NOT NULL,
                     link varchar(255) DEFAULT NULL,
                     teaser varchar(1000) NOT NULL,
                     content text NOT NULL,
                     date_news datetime NOT NULL,
                     author varchar(150) NOT NULL DEFAULT 'Администратор',
                     created_at date NOT NULL,
                     is_active tinyint(1) NOT NULL DEFAULT '0',
                     is_main tinyint(1) NOT NULL DEFAULT '0',
                     is_hot tinyint(1) NOT NULL DEFAULT '0',
                     count_views int(11) unsigned NOT NULL DEFAULT '0',
                     seo_title varchar(150) NOT NULL DEFAULT 'Title',
                     seo_descriptions varchar(300) NOT NULL,
                     seo_keywords varchar(500) NOT NULL,
                     small_img varchar(255) DEFAULT NULL,
                     big_img varchar(255) DEFAULT NULL,
                     PRIMARY KEY (id))
                     ENGINE=InnoDB
                     DEFAULT CHARSET=utf8
                     COLLATE = utf8_general_ci;";
        $register_module_sql ="
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
                    VALUES('newslist',
                           'Список новостей',
                           'news',
                           'news',
                           'index',
                           'admin_news',
                           'index',
                           0,1,1);";
        
        $this->_db->beginTransaction();
        
        $this->_db->getConnection()->exec($sql);
        if (!$this->IsModuleRegistered())
                $this->_db->getConnection()->exec($register_module_sql);
        $this->_db->commit();
    }
    
    protected function UnregisteredModule()
    {
        $sql = "DELETE FROM site_divisions_type WHERE module = 'news'";
        $this->_db->beginTransaction();
        $this->_db->getConnection()->exec($sql);
        $delete_table = "DROP TABLE IF EXISTS ".$this->_tableName;
        $this->_db->getConnection()->exec($delete_table);
        
        $this->_db->commit();
    }
    
    private function DoRoute()
    {
      
      $route_config = new Zend_Config_Ini($this->_news_config->main->config->path.'routes.ini',null,
                              array('skipExtends'        => true,
                                    'allowModifications' => true));
       
        $route_config->news = array();
       // $route_config->news->routes->newslist->type = "Zend_Controller_Router_Route";
        $route_config->news->__set('routes.newslist.type', "Zend_Controller_Router_Route");
        $route_config->news->__set('routes.newslist.route', "newslist/*");
        $route_config->news->__set('routes.newslist.defaults.module', "news");
        $route_config->news->__set('routes.newslist.defaults.controller', "news");
        $route_config->news->__set('routes.newslist.defaults.action', "index");
        $route_config->news->__set('routes.newslist.defaults.id', $this->_ins_id);
        
        $route_config->newsitem = array();
        $route_config->newsitem->__set('routes.newsitem.type',"Zend_Controller_Router_Route");
        $route_config->newsitem->__set('routes.newsitem.route', "newsitem/*");
        $route_config->newsitem->__set('routes.newsitem.defaults.module', "news");
        $route_config->newsitem->__set('routes.newsitem.defaults.controller', "news");
        $route_config->newsitem->__set('routes.newsitem.defaults.action', "newsitem");
        $route_config->newsitem->__set('routes.newsitem.defaults.id', $this->_ins_id);

      
        $writer = new Zend_Config_Writer_Ini();
      
     
        $writer->setFilename($this->_news_config->main->config->path.'routes.ini');
        $writer->setConfig($route_config);
        $writer->write();
        
    }
    
    protected function ClearRoute()
    {
        $route_config = new Zend_Config_Ini($this->_news_config->main->config->path.'routes.ini',null,
                              array('skipExtends'        => true,
                                    'allowModifications' => true));
        
        $route_config->__unset('news');
        $route_config->__unset('newsitem');
        
        
        $writer = new Zend_Config_Writer_Ini();
      
     
        $writer->setFilename($this->_news_config->main->config->path.'routes.ini');
        $writer->setConfig($route_config);
        $writer->write();
        
        
    }
    
    protected function IsModuleRegistered()
    {
        $select  = $this->_db->select();
        $select->from('site_divisions_type')
               ->where('module = ?', $this->_moduleName);
        
        $result = $this->_db->fetchRow($select);
        
        return $res = $result ? true : false;
        
    }
    
   
}
?>
