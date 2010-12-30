<?php

/**
 * Admin_WorksController
 * 
 * @author Grover
 * @version 1.0
 */

class Admin_DivtypeController extends MainAdminController  {
	
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
		$this->layout->title = "Типы разделов сайта";			
		$this->view->caption = 'Параметры';
		$this->view->peoples_id = $this->_getParam('peoples_id');		
		$lang = $this->_getParam('lang','ru');
		//var_dump($this->view->peoples_id);
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
		$this->layout->action_title = "Список элементов";
		$this->view->currentModule = $this->_curModule;
		$where = null;
//		Zend_Debug::dump(SiteDivisionsType::getInstance());

		$this->view->total = count(SiteDivisionsType::getInstance()->fetchAll($where));
		//$this->view->all = $peoples =  Catalog_Params::getInstance()->fetchAll($where, 'priority DESC', (int)$this->_onpage, (int)$this->_offset);
		//$this->view->all = $peoples =  SiteDivisionsType::getInstance()->fetchAll($where, '', (int)$this->_onpage, (int)$this->_offset);
                $this->view->all =  Modules::getInstance()->fetchAll($where, 'priority DESC', (int)$this->_onpage, (int)$this->_offset);
	}
	
	public function editAction(){
		$id = $this->_getParam('id', '');
		if ($id){
			$item = SiteDivisionsType::getInstance()->find($id)->current();
			$this->layout->action_title = "Редактировать элемент";	
		} else{
			$item = SiteDivisionsType::getInstance()->createRow();
			$this->layout->action_title = "Создать элемент";
		}
			
		if ($this->_request->isPost()){						
			$data = $this->trimFilter($this->_getParam('edit'));			
			if ($data['title']!=''){
				$item->setFromArray($data);
				$id =  $item->save();
				
				SiteDivisionsType::getInstance()->updatePages($id, (int)$data['go_to_module']);
								
				$this->view->ok=1;
				
			} else{
				$this->view->err=1;				
			}
			
		}
		
		$this->view->item = $item;
		$modules_rowset = Modules::getInstance()->fetchAll(NULL, 'priority desc');
		$modules = array();
		foreach ($modules_rowset as $module_row){
			$modules[$module_row->name] = $module_row->name;
			
		}
		$this->view->modules = $modules;

		if ($item->id){			
			$this->view->child_name = 'Редактировать';
		} else {
			$this->view->child_name = 'Добавить';
		}	
		
	}
	
 	/**
     * изменение активности
     */
    public function changeactiveAction() {
        // проверка пришел ли запрос аяксом
        if ($this->_request->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $row = SiteDivisionsType::getInstance()->find($id)->current();
            if ($row != null) {
                $row->active = abs($row->active - 1);
                $row->save();
                echo '<img src="/img/admin/active_' . $row->active . '.png" />';
            } else {
                echo 'error';
            }
        }
        exit;
    }

    
 	/**
     * удаление элемента 
     */
    public function deleteAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $row = SiteDivisionsType::getInstance()->find($id)->current();
            if ($row != null) {                
                $row->delete();
                echo 'ok';
            } else {
                echo 'error';
            }
        }
        exit;
    }
	
	
}