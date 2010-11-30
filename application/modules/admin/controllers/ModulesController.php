<?php

/**
 * Admin_ModulesController
 * 
 * @author Grover
 * @version 1.0
 */

class Admin_ModulesController extends MainAdminController  {
	
	/**
 	 * @var string
 	 */
	private $_curModule = null;	
	/**
	 * items per page
	 *
	 * @var int
	 */
	private $_onpage = 20;
	
	private $_page = null;
	/**
	 * offset
	 *
	 * @var int
	 */	
	private $_offset = null;	
	
	public function init(){
		//$this->initView();
		$this->layout = $this->view->layout() ;
		$this->layout->title = "Управление модулями";			
		$this->view->caption = 'Список модулей';			
		$lang = $this->_getParam('lang','ru');		
		$this->view->currentModule = $this->_curModule = SP.'admin'.SP.$lang.SP.$this->getRequest()->getControllerName();
		$this->_page = $this->_getParam('page', 1);	
		$this->_offset =($this->_page-1)*$this->_onpage;	
		$this->view->current_page = $this->_page;
		$this->view->onpage = $this->_onpage;
	
		
	}
	
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$this->layout->action_title = "Список модулей";	
		
                $modules = Modules::getInstance()->ModulesSync();
		//$modules = Modules::getInstance()->fetchAll(null, 'priority DESC')->toArray();		
		$this->view->items = $modules;
	}

	
	
	public function installAction(){
		$name = $this->_getParam('name', '');
		if ($name){
			$path = DIR_MODULES.$name.DS.'comments.xml';		
			$comments = Modules::getInstance()->getComments($name);
			$comments['name'] = $name;
			$handle = fopen(DIR_MODULES.$name.DS.'install.txt', 'w+');
			fwrite($handle, date('Y-m-d H:i:s'));
			fclose($handle);
			$row = Modules::getInstance()->createRow($comments);
			$row->save();	
		}
		$this->_redirect($this->_curModule);
	}
	
	public function addAction(){
		$this->layout->action_title = "Установить модуль";	
		$modules = Modules::getInstance()->getAllModules();
		foreach ($modules as $key=> $module){
				if (file_exists(DIR_MODULES.$module['name'].DS.'install.txt')){
					unset($modules[$key]);
				}
		}
		$this->view->items = $modules;
	}
	
	public function editAction(){
		$name = $this->_getParam('name', '');
		if ($name){
			$module = Modules::getInstance()->fetchRow("name='$name'");
			if ($module!=null){
				$item = $module;
				if ($this->_request->isPost()){						
					$data = $this->trimFilter($this->_getParam('edit'));			
					if ($data['title']!=''){
						$item->setFromArray($data);
						$id =  $item->save();				
						$this->view->ok=1;
					} else{
						$this->view->err=1;				
					}
				
				}
			}
		}
		
		$this->view->item = $item;
		$this->layout->action_title = "Редактировать модуль";
	}

	
	public function deleteAction(){
		$name = $this->_getParam('name', '');
		if ($name ){
			$module = Modules::getInstance()->fetchRow("name='$name'");
			if ($module!=null){
				$module->delete();	
				@unlink(DIR_MODULES.$name.DS.'install.txt');	
			}		
			
			$this->_redirect($this->_curModule);
		}
	}
}