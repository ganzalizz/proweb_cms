<?php

/**
 * Admin_BlocksController
 * 
 * @author Grover
 * @version 1.0
 */

class Blocks_Admin_BlocksController extends MainAdminController  {
	
	/**
 	 * @var string
 	 */
	private $_curModule = null;	
	/**
	 * items per page
	 *
	 * @var int
	 */
	private $_onpage = 50;
	
	/**
	 * items per page
	 *
	 * @var int
	 */
	private $_lang = null;
	
	private $_page = null;
	/**
	 * offset
	 *
	 * @var int
	 */	
	private $_offset = null;	
	
	public function init(){
		//$this->initView();
		$this->layout = $this->view->layout();
		$this->layout->title = "Блоки";			
		$this->view->caption = 'Список блоков';				
		$lang = $this->_getParam('lang','ru');		
		
		$this->view->currentModule = $this->_curModule = SP.$this->getRequest()->getModuleName().SP.$lang.SP.$this->getRequest()->getControllerName();
		$this->_page = $this->_getParam('page', 1);	
		$this->_offset =($this->_page-1)*$this->_onpage;	
		$this->view->current_page = $this->_page;
		$this->view->onpage = $this->_onpage;
	}
	
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$this->layout->action_title = "Список элементов";		
		$this->view->currentModule = $this->_curModule;		
		$where = null;
		$this->view->total = count(Blocks::getInstance()->fetchAll($where));		
		//$this->view->all = $peoples =  Catalog_Params::getInstance()->fetchAll($where, 'priority DESC', (int)$this->_onpage, (int)$this->_offset);
		$this->view->all = $peoples =  Blocks::getInstance()->fetchAll($where, 'priority DESC', (int)$this->_onpage, (int)$this->_offset);		
		
	}
	
	public function editAction(){
		$id = $this->_getParam('id', '');
		if ($id){
			$item = Blocks::getInstance()->find($id)->current();
			$this->layout->action_title = "Редактировать элемент";	
		} else{
			$this->_redirect($this->_curModule);
		}
			
		if ($this->_request->isPost()){						
			$data = $this->trimFilter($this->_getParam('edit'));			
			if ($data['title']!='' && $data['system_name']){
				$item->setFromArray($data);
				$id =  $item->save();	
				Blocks_Cache::clean();			
				$this->view->ok=1;
				
			} else{
				$this->view->err=1;				
			}
			
		}
		if ($item->type=='html'){
			$content = $this->getFck('edit[content]', '90%', '200');
			$this->view->fck_content = $content;
		} 		
		$this->view->item = $item;
	}
	
	public function addAction(){	
		$id = $this->_getParam('id', '');
		if ($id){
			$item = Blocks::getInstance()->find($id)->current();
			$this->layout->action_title = "Редактировать элемент";	
		} else{	
			$item = Blocks::getInstance()->createRow();
			$this->layout->action_title = "Создать элемент";
		}	
			
		if ($this->_request->isPost()){						
			$data = $this->trimFilter($this->_getParam('edit'));			
			if ($data['title']!='' && $data['system_name'] && $data['type']!=''){
				$item->setFromArray($data);
				$id =  $item->save();	
				Blocks_Cache::clean();			
				$this->_redirect($this->_curModule.'/edit/id/'.$id);
				
			} else{
				$this->view->err=1;				
			}
			
		}		
		$this->view->item = $item;
	}
	
	
	/**
	 * изменение активности элемента
	 *
	 */
	public function activeAction(){
		if($this->_hasParam('id')){
			$id = (int)$this->getRequest()->getParam('id');
			$page = Blocks::getInstance()->find($id)->current();			
			if (!is_null($page)){
				$page->active =  abs($page->active-1);
				$page->save();
				Blocks_Cache::clean();
				/*if ($this->_request->isXmlHttpRequest()){
					echo $page->active; exit;
				}*/
			}
						
		}
		$this->_redirect($this->_curModule);
	}

	
	public function deleteAction(){
		$id = $this->_getParam('id');
		if ($id ){
			$item = Blocks::getInstance()->find($id)->current();
			$item->delete();
			Blocks_Cache::clean();			
			
		}
		$this->_redirect($this->_curModule);
	}
}