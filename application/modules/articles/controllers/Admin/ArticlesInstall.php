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
        
        $create_table = "CREATE TABLE IF NOT EXISTS ".$this->_module_tableName."(
                    id int(11) unsigned NOT NULL AUTO_INCREMENT,
                    name varchar(255) NOT NULL,
                    url varchar(150) NOT NULL DEFAULT '',
                    link varchar(255) DEFAULT NULL,
                    teaser varchar(1000) NOT NULL,
                    content text NOT NULL,
                    date datetime NOT NULL,
                    author varchar(150) NOT NULL DEFAULT 'Администратор',
                    created_at date NOT NULL,
                    is_active tinyint(1) NOT NULL DEFAULT '0',
                    is_main tinyint(1) NOT NULL DEFAULT '0',
                    is_hot tinyint(1) NOT NULL DEFAULT '0',
                    lighting tinyint(1) unsigned NOT NULL DEFAULT '0',
                    count_views int(11) unsigned NOT NULL DEFAULT '0',
                    small_img varchar(255) DEFAULT NULL,
                    big_img varchar(255) DEFAULT NULL,
                    seo_title varchar(150) NOT NULL DEFAULT 'Title',
                    seo_descriptions varchar(300) NOT NULL,
                    seo_keywords varchar(500) NOT NULL,
                    PRIMARY KEY (id))
                    ENGINE=InnoDB 
                    DEFAULT CHARSET=utf8
                    COLLATE = utf8_general_ci;";

        $fill_table = "INSERT INTO " . $this->_module_tableName . " (name,
                                                                     url,
                                                                     link,
                                                                     teaser,
                                                                     content,
                                                                     date,
                                                                     author,
                                                                     created_at,
                                                                     is_active,
                                                                     is_main,
                                                                     is_hot,
                                                                     lighting,
                                                                     count_views,
                                                                     small_img,
                                                                     big_img,
                                                                     seo_title,
                                                                     seo_descriptions,
                                                                     seo_keywords) VALUES";

        $fill_counter = 1;
        $fill_count = 40;
        while ($fill_counter <= $fill_count) {
            $fill_table .= $fill_counter > 1 ? ',' : '';
            $fill_table .= "('Тестовая статья " . $fill_counter . "',
                                 'testovaya-statya-" . $fill_counter . "',
                                 'http://easystart" . $fill_counter . ".by',
                                 'Анонс тестовой статьи " . $fill_counter . "',
                                 '<p>Содержание тестовой статьи " . $fill_counter . "</p>',
                                 '".date('Y-m-d h-i-s', strtotime('-'.$fill_counter.' day',time()))."',
                                 'Admin',
                                 '".date('Y-m-d', strtotime('-'.$fill_counter.' day',time()))."',
                                 1, 0, 0, 
                                 ".($fill_counter<=3 ? $fill_counter : 0).",
                                 0,
                                 'demo.jpg', 'demo.jpg',
                                 'Заголовок тестовой статьи  " . $fill_counter . "',
                                 'Описание тестовой статьи " . $fill_counter . "',
                                 'Ключевые слова тестовой статьи " . $fill_counter . "')";
            $fill_counter++;
        }

        // Вычитывание в таблицу site_divisions_type  данных из конфига модуля
        $ini = $this->_module_config->module;
        
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
                    VALUES('" . $ini->sys->name . "',
                           '" . $ini->name . "',
                           '" . $ini->module . "',
                           '" . $ini->controller_frontend . "',
                           '" . $ini->action_frontend . "',
                           '" . $ini->controller_backend . "',
                           '" . $ini->action_backend . "',
                           " . $ini->priority . ",
                           " . $ini->active . ",
                           " . $ini->go_to_module . ");";

//        $a = new PDO ( $dsn , $login , $passwd );
//        $a -> setAttribute ( PDO_MYSQL_ATTR_USE_BUFFERED_QUERY , 1 );
//        $res = $a -> query ( "SELECT ..." );
//        $a -> setAttribute ( PDO_MYSQL_ATTR_USE_BUFFERED_QUERY , 0 );
//        foreach ( $res as $v ) {
//            $a -> exec ( "UPDATE ..." );
//        }
       
        $this->_db->beginTransaction();
        
        $this->_db->getConnection()->exec($create_table);
        $this->_db->getConnection()->exec($fill_table);

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