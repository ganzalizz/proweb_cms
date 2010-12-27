<?php

class Otzivy_Admin_OtzivyController extends MainAdminController {
    
	private $_onPage = null;
	
	private $_curModule = null;

	public function init() {
		$this->view->addScriptPath(DIR_LIBRARY.'Ext/View/Scripts/');
		$module_name = $this->_request->getModuleName();
		
		$ini = new Ext_Common_Config( $module_name, 'backend' );		
		$this->_onPage = $ini->countOnPage;
		$this->view->layout()->title = $ini->module->name;
		
		$lang = $this->_getParam( 'lang', 'ru' );
		$this->view->currentModul = $this->_curModule = SP . $module_name . SP . $lang . SP . $this->getRequest()->getControllerName();
		
	}

    public function indexAction() {
        $this->view->layout()->action_title = "Список элементов";
        $page = $this->view->current_page = $this->_getParam('page', 1);
        
        $prizn = $this->_getParam('prizn', null);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
        $paginator = Otzivy::getInstance()->getAll($page, $this->_onPage, $prizn); 
		$this->view->otzivy = $paginator->getCurrentItems();
		$this->view->paginator = $paginator;
		$this->view->prizn = $prizn;
		$this->view->prin_types = Otzivy::$otziv_vid;
    }
    
	/**
	 * удаление элемента 
	 */
	public function deleteAction() {
		if ($this->_request->isXmlHttpRequest()){			
			$id = $this->_getParam('id');			
			$row = Otzivy::getInstance()->find($id)->current();
			if ($row!=null) {				
				$row->delete();
				echo 'ok';
			} else {
				echo 'error';
			}
		}
		exit;
		
		
	
	}
	/**
	 * изменение активности
	 */
	public function changeactiveAction(){
		// проверка пришел ли запрос аяксом
		if ($this->_request->isXmlHttpRequest()){			
			$id = $this->_getParam('id');			
			$row = Otzivy::getInstance()->find($id)->current();
			if ($row!=null) {
				$row->is_active = abs($row->is_active-1);
				$row->save();
				echo '<img src="/img/admin/active_'.$row->is_active.'.png" />';
			} else {
				echo 'error';
			}
		}
		exit;
		
	}
	
	/**
	 * изменение отображение на главной
	 */
	public function changemainAction(){
		// проверка пришел ли запрос аяксом
		if ($this->_request->isXmlHttpRequest()){			
			$id = $this->_getParam('id');			
			$row = Otzivy::getInstance()->find($id)->current();
			if ($row!=null) {
				$row->is_main = abs($row->is_main-1);
				$row->save();
				echo '<img src="/img/admin/main_'.$row->is_main.'.gif" />';
			} else {
				echo 'error';
			}
		}
		exit;
		
	}
        
         public function installAction() 
        {
            require_once 'OtzivyInstall.php';
            $install = new OtzivyInstall('otzivy');
            $install->Install();
            $this->_redirect("/admin/ru/modules");
        }
        
        public function uninstallAction()
        {
            require_once 'OtzivyInstall.php';
	    $install = new OtzivyInstall('otzivy');
	    $install->Uninstall();
            $this->_redirect("/admin/ru/modules");
        }



   

    

    

}