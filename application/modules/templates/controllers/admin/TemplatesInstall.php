<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


require_once APPLICATION_PATH . '/../library/Ext/Common/InstallModuleAbstract.php';

class TemplatesInstall extends Ext_Common_InstallModuleAbstract {

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
                                          describe_template text NOT NULL,
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

        $fill_table = "INSERT INTO " . $this->_module_tableName . " (title,
                                                                 url,
                                                                 describe_template,
                                                                 price,
                                                                 template_image,
                                                                 is_active,
                                                                 count_views,
                                                                 seo_title,
                                                                 seo_descriptions,
                                                                 seo_keywords) VALUES";

        $fill_counter = 1;
        $fill_count = 40;
        while ($fill_counter <= $fill_count) {
            $fill_table .= $fill_counter > 1 ? ',' : '';
            $fill_table .= "('Тестовый шаблон " . $fill_counter . "',
                             'testoviy-shablon-" . $fill_counter . "',
                             'Тестовое описание " . $fill_counter . "',
                             10000, 'demo.jpg', 1, 0,
                             'Заголовок тестового шаблона  " . $fill_counter . "',
                             'Описание тестового шаблона " . $fill_counter . "',
                             'Ключевые слова тестового шаблона " . $fill_counter . "')";
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

        $this->_db->getConnection()->exec($create_table);
        $this->_db->getConnection()->exec($fill_table);

        if (!$this->IsModuleRegistered())
            $this->_db->getConnection()->exec($register_module_sql);

        $where = $this->_db->quoteInto('name = ?', 'templates');
        $this->_db->update('site_modules', array('installed' => 1), $where);
        $this->_db->commit();
    }

    protected function UnregisteredModule() {
        $delete_table = "DROP TABLE IF EXISTS " . $this->_module_tableName;
        $this->_db->beginTransaction();

        $this->_db->exec($delete_table);

        $where = $this->_db->quoteInto('module = ?', 'templates');
        $this->_db->delete('site_divisions_type', $where);
        $where = $this->_db->quoteInto('name = ?', 'templates');
        $this->_db->update('site_modules', array('installed' => 0), $where);

        $this->_db->commit();
    }

}
