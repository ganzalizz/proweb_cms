<?php

/**
 * Admin_BlocksController
 *
 * @author Maks Sherstobitow
 * @version 1.0
 */

class Constructor_Admin_SizesController extends MainAdminController  {

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
		$this->layout->title = "Конструктор";
		$this->view->caption = 'Размеры блюд';
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
                $id = $this->_getParam('type_id', '');
		if ($id){
                        $type_name = Constructor_Types::getInstance()->getNameById($id);
                        $this->layout->action_title = "Список размеров блюда";
                        if ($type_name) $this->layout->action_title .= " для \"$type_name\"";
                        $this->view->currentModule = $this->_curModule;
                        $this->view->type_id = $id;
                        $this->view->total = count(Constructor_Sizes::getInstance()->fetchAll());
                        $this->view->all = Constructor_Sizes::getInstance()->fetchAll("id_type=$id", 'priority DESC', (int)$this->_onpage, (int)$this->_offset);
		} else {
			$this->_redirect('/constructor/ru/admin_types/');
		}
		
	}

	public function editAction(){
		$type_id = $this->_getParam('type_id', '');
                $id = $this->_getParam('id', '');
		if ($id){
			$item = Constructor_Sizes::getInstance()->find($id)->current();
			$this->layout->action_title = "Редактировать размер блюда";
		} else{
			$item = Constructor_Sizes::getInstance()->fetchNew();
                        $this->layout->action_title = "Создать размер блюда";
		}
                $type_name = Constructor_Types::getInstance()->getNameById($type_id);
                if ($type_name) $this->layout->action_title .= " для \"$type_name\"";

		if ($this->_request->isPost()){
			$data = $this->trimFilter($this->_getParam('edit'));
                        $data['id_type'] = $type_id;
			if ($data['title']!=''){
				$item->setFromArray($data);
				$id =  $item->save();
				$this->view->ok=1;

			} else{
				$this->view->err=1;
			}
		}
		$this->view->item = $item;
                $this->view->type_id = $type_id;
                
	}

	
	/**
	 * изменение активности элемента
	 *
	 */
	public function activeAction(){
		if($this->_hasParam('id')){
			$id = (int)$this->getRequest()->getParam('id');
			$page = Constructor_Sizes::getInstance()->find($id)->current();
			if (!is_null($page)){
				$page->active =  abs($page->active-1);
				$page->save();
			}
                        $this->_redirect($this->_curModule.'/index/type_id/'.$this->_getParam('type_id'));
		}
		$this->_redirect($this->_curModule);
	}


	public function deleteAction(){
		$id = $this->_getParam('id', '');
		if ($id ){
			$item = Constructor_Sizes::getInstance()->find($id)->current();
			$item->delete();
		}
                $type_id = $this->_getParam('type_id');
                if ($type_id)
                    $this->_redirect($this->_curModule.'/index/type_id/'.$type_id);
                else
                    $this->_redirect($this->_curModule);
	}
}