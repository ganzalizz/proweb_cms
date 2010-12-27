<?php

require_once APPLICATION_PATH.'/../library/Ext/Common/InstallModuleAbstract.php';

class ArticlesInstall extends Ext_Common_InstallModuleAbstract
{
    
     
    //TODO: Сделать префикс для названия таблиц
    
    
    
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
        
        $sql = "CREATE TABLE IF NOT EXISTS ".$this->_module_tableName."(
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
                    lighting tinyint(1) unsigned NOT NULL DEFAULT '0',
                    count_views int(11) unsigned NOT NULL DEFAULT '0',
                    seo_title varchar(150) NOT NULL DEFAULT 'Title',
                    seo_descriptions varchar(300) NOT NULL,
                    seo_keywords varchar(500) NOT NULL,
                    small_img varchar(255) DEFAULT NULL,
                    big_img varchar(255) DEFAULT NULL,
                    PRIMARY KEY (id))
                    ENGINE=InnoDB 
                    DEFAULT CHARSET=utf8;
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
                    VALUES('articleslist',
                           'Список статей',
                           'articles',
                           'articles',
                           'index',
                           'admin_articles',
                           'index',
                           0,1,1);";
       
       
        $this->_db->beginTransaction();
        
        $this->_db->getConnection()->exec($sql);
        if (!$this->IsModuleRegistered())
                $this->_db->getConnection()->exec($register_module_sql);
                $where = $this->_db->quoteInto('name = ?', 'articles');
        $this->_db->update('site_modules', array('installed' => 1), $where);
        $this->_db->commit();
    }
    
    protected function UnregisteredModule()
    {
        $this->_db->beginTransaction();
        $where = $this->_db->quoteInto('module = ?', 'articles');
        $this->_db->delete('site_divisions_type', $where);
        
        $delete_table = "DROP TABLE IF EXISTS ".$this->_module_tableName;
        $this->_db->getConnection()->exec($delete_table);
        $where = $this->_db->quoteInto('name = ?', 'articles');
        $this->_db->update('site_modules', array('installed' => 0), $where);
        $this->_db->commit();
    }
    
}