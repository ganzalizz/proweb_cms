<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once APPLICATION_PATH . '/../library/Ext/Common/InstallModuleAbstract.php';

class ContactsInstall extends Ext_Common_InstallModuleAbstract
{
     public function Install()
    {
       
        $this->RegisterModule();
        
    }
    
    public function Uninstall()
    {
        
        $this->UnregisteredModule();
       
    }
    
    private function RegisterModule()
    {
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
               
        $this->_db->beginTransaction();
        
        if (!$this->IsModuleRegistered())
            $this->_db->getConnection()->exec($register_module_sql);
        
        $where = $this->_db-> quoteInto('name = ?', 'contacts');
        $this->_db->update('site_modules', array('installed' => 1), $where);
        $this->_db->commit();
    }
    
    protected function UnregisteredModule()
    {
        $this->_db->beginTransaction();
        $where = $this->_db->quoteInto('module = ?', 'contacts');
        $this->_db->delete('site_divisions_type', $where);
        $where = $this->_db->quoteInto('name = ?', 'contacts');
        $this->_db->update('site_modules', array('installed' => 0), $where);
        $this->_db->commit();
    }
}
