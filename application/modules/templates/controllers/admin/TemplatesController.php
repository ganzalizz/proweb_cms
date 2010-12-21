<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Templates_Admin_TemplatesController extends MainAdminController
{
        private $_id_page = null;
	/**
	 * Pages_row
	 * @var object
	 */
	private $_owner = null;
	
	private $_curModule = null;
	
	private $_basePicsPath = null;
	
	private $_onPage = null;
        
        public function init() {
		$this->view->addHelperPath( Zend_Registry::get( 'helpersPaths' ), 'View_Helper' );
		$ini = new Ext_Common_Config( 'templates', 'backend' );
		$this->_basePicsPath = $ini->basePicsPath;
		$this->_onPage = $ini->countOnPage;
		
		$this->checkDirs();
		
		$this->view->lang = $lang = $this->_getParam( 'lang', 'ru' );
		$this->view->currentModul = $this->_curModule = SP . 'news' . SP . $lang . SP . $this->getRequest()->getControllerName();
	}
        
        public function installAction() 
        {
            require_once 'TemplatesInstall.php';
            $install = new TemplatesInstall('templates');
            $install->Install();
            $this->_redirect("/admin/ru/modules");
        }
        
        public function uninstallAction()
        {
            require_once 'TemplatesInstall.php';
	    $install = new TemplatesInstall('templates');
	    $install->Uninstall();
            $this->_redirect("/admin/ru/modules");
        }
}
