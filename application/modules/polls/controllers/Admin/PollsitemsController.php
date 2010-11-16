<?php

class Polls_Admin_PollsitemsController extends MainAdminController  {
	
	/**
 	 * @var string
 	 */
	private $_curModule = null;
        private $_curCategoryId = null;
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
                $title = Polls::getInstance()->getPollNameBiId($this->_getParam('poll_id'));
                if ($title)
                    $this->layout->title = "Опросы: ".$title;
                else
                    $this->layout->title = "Опросы";
		$this->view->caption = 'Список ответов';
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
            $this->layout->action_title = "Список ответов";
            $this->view->currentModule = $this->_curModule;
            $poll_id = $this->_hasParam('poll_id') ? $this->_getParam('poll_id') : NULL;
            if (!$poll_id) {
                $this->_forward('index', 'admin_polls');
                return;
            }
            $items = Pollsitems::getInstance()->getItemsByParentId($poll_id, $this->_onpage, $this->_offset);
            if (count($items)) {
                $items = $items->toArray();
            }
            else {
                $items = array();
            }
            $this->view->parent_id = $poll_id;
            $this->view->all =  $items;
                
		
	}

	public function editAction(){
		$id = $this->_getParam('id', '');
                $poll_id = $this->_getParam('poll_id');
		if ($poll_id && $id){
			$item = Pollsitems::getInstance()->find($id)->current();
			$this->layout->action_title = "Редактировать ответ: ".$item->title;
		} elseif($poll_id){
			$item = Pollsitems::getInstance()->createRow();
			$this->layout->action_title = "Создать ответ";
		}
                else {
                    $this->_forward('index', 'admin_polls');
                    return;
                }
			
		if ($this->_request->isPost()){	
			$data = $this->trimFilter($this->_getParam('edit'));
                        $data['id_parent'] = $poll_id;
			if ($data['title']!=''){
				$item->setFromArray($data);
				$id =  $item->save();				
				$this->view->ok=1;
				
			} else{
				$this->view->err=1;				
			}
			
		}
		$this->view->item = $item;
		$this->view->parent_id = $poll_id;
		$this->view->item = $item;
	}
	/**
	 * изменение активности элемента
	 *
	 */
	public function activeAction(){
            if($this->_hasParam('id')){
                $id = (int)$this->getRequest()->getParam('id');                
                $page = Pollsitems::getInstance()->find($id)->current();
                
                if (!is_null($page)){
                        $page->active =  abs($page->active-1);
                        $page->save();
                }
            }
            $this->_redirect($this->_curModule.'/index/poll_id/'.$this->_getParam('poll_id'));
        }
	

	
	public function deleteAction(){
            $id = $this->_getParam('id');
            $poll_id = $this->_getParam('poll_id');
            if ($id ){                
                $item = Pollsitems::getInstance()->find($id)->current();
                $item->delete();
                $this->_redirect($this->_curModule.'/index/poll_id/'.$this->_getParam('poll_id'));
            }
	}
}