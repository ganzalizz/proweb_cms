<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Ext_Common_InstallModule extends Ext_Common_InstallModuleAbstract
{
    
   public static function getInstance($module_sys_name)
   {   echo "Instance";

        if (self::$instance === null)
        {
            self::$instance = new self($module_sys_name);
        }
        return self::$instance;

   }
   /**
    * 
    */
    private final function __clone()
    {
        trigger_error( "Cannot clone instance of Singleton pattern", E_USER_ERROR );
    }
    
    public function Install(){}
   
    
    public function Uninstall(){}
    
}