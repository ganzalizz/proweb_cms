<?php

class Contacts_Admin_ContactsController extends MainAdminController
{
  
  /**
   * Регистрация модуля в системе 
   */  
  public function installAction()
  {
      require_once 'ContactsInstall.php';
      $module = new Contacts_Admin_ContactsInstall('contacts');
      $module->Install();
      $this->_redirect("/admin/ru/modules");
  }
  /**
   * 
   */
  public function uninstallAction()
  {
      require_once 'ContactsInstall.php';
      $module = new Contacts_Admin_ContactsInstall('contacts');
      $module->Uninstall();
      $this->_redirect("/admin/ru/modules");
  }
}

