<?php

/**
 * Admin_WorksController
 * 
 * @author Grover
 * @version 1.0
 */

class Polls_Admin_PollsController extends MainAdminController  {
	
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
                $title = Polls::getInstance()->getPollNameBiId($this->_getParam('id'));
                if ($title)
                    $this->layout->title = "Опросы: ".$title;
                else
                    $this->layout->title = "Опросы";
		$this->view->caption = 'Список опросов';
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
            $this->view->lang = $this->_hasParam('lang') ? $this->_getParam('lang') : 'ru';
            $this->layout->action_title = "Список опросов";
            $this->view->currentModule = $this->_curModule;
            $polls = Polls::getInstance()->getActivePolls(false, $this->_onpage, $this->_offset);
            if (!count($polls))
                $polls = array();
            $this->view->all =  $polls;
                
		
	}

	public function editAction(){
		$id = $this->_getParam('id', '');
		if ($id){
			$item = Polls::getInstance()->find($id)->current();
			$this->layout->action_title = "Редактировать опрос";
		} else{
			$item = Polls::getInstance()->createRow();
			$this->layout->action_title = "Создать опрос";
		}
			
		if ($this->_request->isPost()){						
			$data = $this->trimFilter($this->_getParam('edit'));			
			if ($data['title']!=''){
                                $data['priority'] = (int)$data['priority'];
				$item->setFromArray($data);
				$id =  $item->save();				
				$this->view->ok=1;
				
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
			$page = Polls::getInstance()->find($id)->current();
			
			if (!is_null($page)){
				$page->active =  abs($page->active-1);
				$page->save();
			}
						
		}
		$this->_redirect($this->_curModule);
	}

	
	public function deleteAction(){
		$id = $this->_getParam('id');
		if ($id ){
			$item = Polls::getInstance()->find($id)->current();
                        Pollsitems::getInstance()->delete("id_parent = " . $id);
			$item->delete();			
			$this->_redirect($this->_curModule);
		}
	}
}