<?php

require_once APPLICATION_PATH . '/../library/Ext/Common/InstallModuleAbstract.php';

class PortfolioInstall extends Ext_Common_InstallModuleAbstract {

    public function Install() {


        $this->RegisterModule();
        $this->DoRoute();
    }

    public function Uninstall() {

        $this->UnregisteredModule();
        $this->DeleteRoute();
    }

    private function RegisterModule() {
        $create_table = "CREATE TABLE IF NOT EXISTS " . $this->_module_tableName . "(
                                          id int(11) unsigned NOT NULL AUTO_INCREMENT,
                                          title varchar(255) NOT NULL,
                                          url varchar(150) NOT NULL,
                                          link varchar(255) NOT NULL,
                                          content text,
                                          is_active tinyint(1) NOT NULL DEFAULT '0',
                                          is_main tinyint(1) NOT NULL DEFAULT '0',
                                          date_project date DEFAULT NULL,
                                          created_at date DEFAULT NULL,
                                          pub_date datetime DEFAULT NULL,
                                          image varchar(255) DEFAULT NULL,
                                          seo_title varchar(150) NOT NULL,
                                          seo_descriptions varchar(300) NOT NULL,
                                          seo_keywords varchar(500) NOT NULL,
                                          PRIMARY KEY (id))
                                          ENGINE=InnoDB
                                          DEFAULT CHARSET=utf8
                                          COLLATE = utf8_general_ci;";

        $fill_table = "INSERT INTO " . $this->_module_tableName . " (title,
                                                                     url,
                                                                     link,
                                                                     content,
                                                                     is_active,
                                                                     is_main,
                                                                     date_project,
                                                                     created_at,
                                                                     pub_date,
                                                                     image,
                                                                     seo_title,
                                                                     seo_descriptions,
                                                                     seo_keywords) VALUES";

        $fill_counter = 1;
        $fill_count = 40;
        while ($fill_counter <= $fill_count) {
            $fill_table .= $fill_counter > 1 ? ',' : '';
            if ($fill_counter < $fill_count / 2)
                $date = strtotime('-'.$fill_counter.' day',time());
            else
                $date = strtotime('-1 year -'.$fill_counter.' day ',time());
            $fill_table .= "('Тестовый элемент " . $fill_counter . "',
                                 'testoviy-element-" . $fill_counter . "',
                                 'http://easystart" . $fill_counter . ".by',
                                 '<p>Содержание тестового элемента " . $fill_counter . "</p>',
                                 1, 0,
                                 '".date('Y-m-d', $date)."',
                                 '".date('Y-m-d', $date)."',
                                 '".date('Y-m-d h-i-s', $date)."',
                                 'demo.jpg',
                                 'Заголовок тестового элемента  " . $fill_counter . "',
                                 'Описание тестового элемента " . $fill_counter . "',
                                 'Ключевые слова тестового элемента " . $fill_counter . "')";
            $fill_counter++;
        }

        // Вычитывание в таблицу site_divisions_type  данных из конфига модуля
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

        $this->_db->getConnection()->exec($create_table);
        $this->_db->getConnection()->exec($fill_table);

        if (!$this->IsModuleRegistered())
            $this->_db->getConnection()->exec($register_module_sql);

        $where = $this->_db->quoteInto('name = ?', 'portfolio');
        $this->_db->update('site_modules', array('installed' => 1), $where);
        $this->_db->commit();
    }

    protected function UnregisteredModule() {
        $delete_table = "DROP TABLE IF EXISTS " . $this->_module_tableName;
        $this->_db->beginTransaction();

        $this->_db->exec($delete_table);

        $where = $this->_db->quoteInto('module = ?', 'portfolio');
        $this->_db->delete('site_divisions_type', $where);
        $where = $this->_db->quoteInto('name = ?', 'portfolio');
        $this->_db->update('site_modules', array('installed' => 0), $where);

        $this->_db->commit();
    }

}
