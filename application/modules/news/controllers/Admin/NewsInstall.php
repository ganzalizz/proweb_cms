<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once APPLICATION_PATH . '/../library/Ext/Common/InstallModuleAbstract.php';

class NewsInstall extends Ext_Common_InstallModuleAbstract {

    //TODO: Сделать префикс для названия таблиц
    protected $_ins_id = null;

    public function Install() {

        $this->RegisterModule();
        $this->DoRoute();
    }

    public function Uninstall() {
        $this->UnregisteredModule();
        $this->DeleteRoute();
    }

    private function RegisterModule() {

        $sql = "CREATE TABLE IF NOT EXISTS " . $this->_module_tableName . " (
                     id int(11) unsigned NOT NULL AUTO_INCREMENT,
                     name varchar(255) NOT NULL,
                     url varchar(150) NOT NULL,
                     link varchar(255) DEFAULT NULL,
                     teaser varchar(1000) NOT NULL,
                     content text NOT NULL,
                     date_news datetime NOT NULL,
                     author varchar(150) NOT NULL DEFAULT 'Администратор',
                     created_at date NOT NULL,
                     is_active tinyint(1) NOT NULL DEFAULT '0',
                     is_main tinyint(1) NOT NULL DEFAULT '0',
                     is_hot tinyint(1) NOT NULL DEFAULT '0',
                     lighting int(1) unsigned NOT NULL DEFAULT '0',
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

        $fill_table = "INSERT INTO " . $this->_module_tableName . " (name,
                                                                 url,
                                                                 link,
                                                                 teaser,
                                                                 content,
                                                                 date_news,
                                                                 author,
                                                                 created_at,
                                                                 is_active,
                                                                 is_main,
                                                                 is_hot,
                                                                 count_views,
                                                                 seo_title,
                                                                 seo_descriptions,
                                                                 seo_keywords,
                                                                 small_img,
                                                                 big_img) VALUES";

        $fill_counter = 1;
        $fill_count = 20;
        $fill_count_on_main = 3;
        while ($fill_counter <= $fill_count) {
            $fill_table .= $fill_counter > 1 ? ',' : '';
            $fill_table .= "('Тестовая новость " . $fill_counter . "',
                             'testovaya-novost-" . $fill_counter . "',
                             'http://easystart" . $fill_counter . ".by',
                             'Краткое описание тестовой новости " . $fill_counter . "',
                             'Полное описание тестовой новости " . $fill_counter . "',
                             '".date('Y-m-d', strtotime('-'.$fill_counter.' day',time()))."',
                             'Admin',
                             '".date('Y-m-d', strtotime('-'.$fill_counter.' day',time()))."',
                             1,
                             ".($fill_counter<=$fill_count_on_main ? 1 : 0).",
                             0,0,
                             'Заголовок тестовой новости " . $fill_counter . "',
                             'Описание тестовой новости " . $fill_counter . "',
                             'Ключевые слова тестовой новости " . $fill_counter . "',
                             'demo.jpg', 'demo.jpg')";
            $fill_counter++;
        }

        // Вычитывание в таблицу site_divisions_type данных из конфига модуля
        $ini = $this->_module_config->module;

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


        $this->_db->beginTransaction();

        $this->_db->getConnection()->exec($sql);
        $this->_db->getConnection()->exec($fill_table);
        if (!$this->IsModuleRegistered())
            $this->_db->getConnection()->exec($register_module_sql);
        $where = $this->_db->quoteInto('name = ?', 'news');
        $this->_db->update('site_modules', array('installed' => 1), $where);
        $this->_db->commit();
    }

    protected function UnregisteredModule() {
        $this->_db->beginTransaction();
        $where = $this->_db->quoteInto('module = ?', 'news');
        $this->_db->delete('site_divisions_type', $where);

        $delete_table = "DROP TABLE IF EXISTS " . $this->_module_tableName;
        $this->_db->getConnection()->exec($delete_table);
        $where = $this->_db->quoteInto('name = ?', 'news');
        $this->_db->update('site_modules', array('installed' => 0), $where);
        $this->_db->commit();
    }

}

