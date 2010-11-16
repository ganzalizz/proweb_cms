<?php

/**
 * Admin_BlocksController
 *
 * @author Maks Sherstobitow
 * @version 1.0
 */

class Constructor_Admin_TypesController extends MainAdminController  {

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
		$this->view->caption = 'Типы блюд';
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
		$this->layout->action_title = "Список типов блюд";
		$this->view->currentModule = $this->_curModule; 
		$this->view->total = count(Constructor_Types::getInstance()->fetchAll());
		$this->view->all = Constructor_Types::getInstance()->fetchAll(null, 'priority DESC', (int)$this->_onpage, (int)$this->_offset);
	}

	public function editAction(){
		$id = $this->_getParam('type_id', '');
		if ($id){
			$item = Constructor_Types::getInstance()->find($id)->current();
			$this->layout->action_title = "Редактировать тип блюда";
		} else{
			$item = Constructor_Types::getInstance()->fetchNew();
                        $this->layout->action_title = "Создать тип блюда";
		}

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
		if($this->_hasParam('type_id')){
			$id = (int)$this->getRequest()->getParam('type_id');
			$page = Constructor_Types::getInstance()->find($id)->current();
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
			$item = Constructor_Types::getInstance()->find($id)->current();
			$item->delete();

		}
		$this->_redirect($this->_curModule);
	}
}