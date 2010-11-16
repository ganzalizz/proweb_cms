<?php

/**
 * Admin_BlocksController
 *
 * @author Maks Sherstobitow
 * @version 1.0
 */

class Constructor_Admin_PricesController extends MainAdminController  {

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
            if ($this->_request->isPost()) $this->edit();
            $id = $this->_getParam('type_id', '');
            if ($id){
                    $type_name = Constructor_Types::getInstance()->getNameById($id);
                    $this->layout->action_title = "Список размеров блюда";
                    if ($type_name) $this->layout->action_title .= " для \"$type_name\"";
                    $this->view->currentModule = $this->_curModule;
                    $this->view->type_id = $id;
                    
                    $groups = Constructor_Groups::getInstance()->getGroupsByTypeId($id);
                    $groups = Constructor_Groups_Items::getInstance()->insertItemsByGroupId($groups);
                    $groups = Constructor_Sizes::getInstance()->insertSizesByTypeId($groups, $id);
                    $groups = Constructor_Prices::getInstance()->insertPricesByTypeId($groups, $id);
                    $this->view->sizes = Constructor_Sizes::getInstance()->getSizesByTypeId($id);
                    $this->view->groups = $groups;
            } else {
                    $this->_redirect('/constructor/ru/admin_types/');
            }
	}

	public function edit(){
            $type_id = (int)$this->_getParam('type_id', '');

            if ($this->_request->isPost()){
                $data = $this->_getParam('prices');
                if ($type_id){
                    foreach($data as $item_id=>$sizes){
                    
                        foreach($sizes as $size_id=>$price){
                            $id = Constructor_Prices::getInstance()->getRowId($type_id, $item_id, $size_id);
                            if ($id){
                                $item = Constructor_Prices::getInstance()->find($id)->current();
                            } else{
                                $item = Constructor_Prices::getInstance()->fetchNew();
                            }
                            $saveData = array();
                            $saveData['id_type'] = $type_id;
                            $saveData['id_item'] = $item_id;
                            $saveData['id_size'] = $size_id;
                            $saveData['price'] = (int)$price;
                            $item->setFromArray($saveData);
                            $id =  $item->save();
                        }
                    }
                    $this->view->ok=1;

                } else {
                    $this->view->err=1;
                }
            }
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