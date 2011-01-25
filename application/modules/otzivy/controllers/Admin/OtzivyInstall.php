<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once APPLICATION_PATH.'/../library/Ext/Common/InstallModuleAbstract.php';

class OtzivyInstall extends Ext_Common_InstallModuleAbstract
{
   public function  Install() 
   {
       $this->RegisterModule();
       
   }
   
   public function  Uninstall() 
   {
       $this->UnregisteredModule();
       
   }
   
   private function RegisterModule()
    {
        $create_table = "CREATE TABLE IF NOT EXISTS ".$this->_module_tableName."(
                                id int(11) NOT NULL AUTO_INCREMENT,
                                name varchar(60) NOT NULL,
                                email varchar(255) NOT NULL,
                                added date NOT NULL,
                                prizn tinyint(1) unsigned NOT NULL DEFAULT '0',
                                content varchar(3000) NOT NULL,
                                is_active tinyint(1) NOT NULL DEFAULT '0',
                                is_main tinyint(1) unsigned NOT NULL DEFAULT '0',
                                PRIMARY KEY (id))
                                ENGINE=InnoDB
                                DEFAULT CHARSET=utf8
                                COLLATE = utf8_general_ci;";

         $fill_table = "INSERT INTO " . $this->_module_tableName . " (name,
                                                                 email,
                                                                 added,
                                                                 prizn,
                                                                 content,
                                                                 is_active,
                                                                 is_main) VALUES";

        $fill_counter = 1;
        $fill_count = 20;
        while ($fill_counter <= $fill_count) {
            $fill_table .= $fill_counter > 1 ? ',' : '';
            $fill_table .= "('Пользователь " . $fill_counter . "',
                             'demo" . $fill_counter . "@easystart.by',
                             '" . date('Y-m-d', strtotime('-'.$fill_counter.' day',time())) . "',
                             " . ($fill_counter%2==0 ? 1 : 0) . ",
                             'Содержание тестового " . ($fill_counter%2==0 ? 'предложения' : 'отзыва') . "',
                             1,0)";
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
        
                $where = $this->_db-> quoteInto('name = ?', 'otzivy');
                $this->_db->update('site_modules', array('installed' => 1), $where);
        $this->_db->commit();
    }
    
     protected function UnregisteredModule()
    {
        $delete_table = "DROP TABLE IF EXISTS ".$this->_module_tableName;
        $this->_db->beginTransaction();
        
        $this->_db->exec($delete_table);
        
        $where = $this->_db->quoteInto('module = ?', 'otzivy');
        $this->_db->delete('site_divisions_type', $where);
        $where = $this->_db->quoteInto('name = ?', 'otzivy');
        $this->_db->update('site_modules', array('installed' => 0), $where);
        
        $this->_db->commit();
    }
    
    
   
}
