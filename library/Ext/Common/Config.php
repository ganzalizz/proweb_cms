<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Ext_Common_Config extends Zend_Config_Ini
{
    
 protected $_file_path = null;
 
 protected $_section = null;
 
 protected $_iniData = null;
 
 /**
 *  Конструктор загружает указанную секцию ini файла если указана если нет весь ini файл
 *
 * @param $module - имя модуля
 * @param $section - имя секции в ini файле
 *
 * @return object - экземпляр объекта Zend_Config_Ini
 *
 * @see Zend_Config_Ini
 */
 public function  __construct($module,$section = null)
 {
     
         if ($this->IsIniFileExist($module))
         {
             parent::__construct($this->_file_path, $section);
             $this->_section = $section;
             $this->_iniData = $this;
         }
         else
         {
             print "Not module config file found";
         }
     
        
  }
    
 /**
 * Строит путь к файлу конфигурации в модуле
 *
 * @param $module - имя модуля
 *
 * @return path - полный путь к файлу включая имя файла 
 *
 * @see
 */    
 protected function setFilePath($module)
 {
     $this->_file_path = APPLICATION_PATH.'/modules/'.$module.'/config/'.$module.'.ini';
    
 }
 
/**
 * Проверяет есть ли конфиг файл у модуля
 *
 * @param $module - имя модуля
 *
 * @return path - полный путь к файлу включая имя файла или false если файл не найден
 *
 * 
 */ 
   protected function IsIniFileExist($module)
   {
       $this->setFilePath($module);
       
       if (file_exists($this->_file_path))
               return $this;
       else
               return false; 
   }
   
/**
 * 
 * Загружает в реестр секцию ini файла указанную в кострукторе
 *
 * @return $registry - объект Zend_Registry 
 *
 * @see Zend_Registry
 */    
   public function getModuleConfigSection()
   {
       
       $registry = Zend_Registry::getInstance();
       
       if (!$registry->isRegistered($this->_section))
               $registry->set ($this->_section, $this->_iniData);
       return $registry;
          
   }
    
}

