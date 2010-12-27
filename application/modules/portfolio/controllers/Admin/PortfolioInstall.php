<?php

require_once APPLICATION_PATH.'/../library/Ext/Common/InstallModuleAbstract.php';

class PortfolioInstall extends Ext_Common_InstallModuleAbstract
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
                                          type varchar(50) NOT NULL DEFAULT 'portfolio',
                                          title varchar(255) NOT NULL,
                                          url varchar(255) NOT NULL,
                                          teaser varchar(1000) NOT NULL,
                                          content text,
                                          is_active tinyint(1) NOT NULL DEFAULT '0',
                                          main int(1) NOT NULL DEFAULT '0',
                                          date_project date DEFAULT NULL,
                                          created_at date DEFAULT NULL,
                                          pub_date datetime DEFAULT NULL,
                                          seo_title varchar(150) NOT NULL,
                                          seo_descriptions varchar(300) NOT NULL,
                                          seo_keywords varchar(500) NOT NULL,
                                          PRIMARY KEY (id))
                                          ENGINE=InnoDB
                                          DEFAULT CHARSET=utf8
                                          COLLATE = utf8_general_ci;";
          //TODO: Сделать вычитывание в таблицу site_divisions_type  данных из конфига модуля  
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
                    VALUES('portfolio',
                           'portfolio',
                           'portfolio',
                           'portfolio',
                           'index',
                           'admin_portfolio',
                           'index',
                           0,1,1);";
               
        $this->_db->beginTransaction();
        
        $this->_db->getConnection()->exec($create_table);
        
        if (!$this->IsModuleRegistered())
                $this->_db->getConnection()->exec($register_module_sql);
        
                $where = $this->_db-> quoteInto('name = ?', 'portfolio');
                $this->_db->update('site_modules', array('installed' => 1), $where);
        $this->_db->commit();
    }
    
    protected function UnregisteredModule()
    {
        $delete_table = "DROP TABLE IF EXISTS ".$this->_module_tableName;
        $this->_db->beginTransaction();
        
        $this->_db->exec($delete_table);
        
        $where = $this->_db->quoteInto('module = ?', 'portfolio');
        $this->_db->delete('site_divisions_type', $where);
        $where = $this->_db->quoteInto('name = ?', 'portfolio');
        $this->_db->update('site_modules', array('installed' => 0), $where);
        
        $this->_db->commit();
    }
    
  
}
