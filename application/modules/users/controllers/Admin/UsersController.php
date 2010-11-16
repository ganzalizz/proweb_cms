<?php

/**
 * Users_Admin_UsersController
 * 
 * @author Grover
 * @version 1.0
 */

class Users_Admin_UsersController extends MainAdminController  {
	
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
		$this->layout->title = "Пользователи сайта";			
		$this->view->caption = 'Список пользователей';				
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
		$this->layout->action_title = "Список пользователей";		
		$this->view->currentModule = $this->_curModule;		
		$where = null;
		$this->view->total = count(SiteUsers::getInstance()->fetchAll($where));		
		//$this->view->all = $peoples =  Catalog_Params::getInstance()->fetchAll($where, 'added DESC', (int)$this->_onpage, (int)$this->_offset);
		$this->view->all = $peoples =  SiteUsers::getInstance()->fetchAll($where, 'added DESC', (int)$this->_onpage, (int)$this->_offset);		
		
	}
	
	/**
	 * The default action - show the home page
	 */
	public function unactiveAction() {
		$this->layout->action_title = "Неактивные пользователи";		
		$this->view->currentModule = $this->_curModule;		
		$where = "active='0'";
		$this->view->total = count(SiteUsers::getInstance()->fetchAll($where));		
		//$this->view->all = $peoples =  Catalog_Params::getInstance()->fetchAll($where, 'added DESC', (int)$this->_onpage, (int)$this->_offset);
		$this->view->all = $peoples =  SiteUsers::getInstance()->fetchAll($where, 'added DESC', (int)$this->_onpage, (int)$this->_offset);
		//$this->render('index');
				
		
	}
	
	public function editAction(){
		$users_model = SiteUsers::getInstance();
		$id = $this->_getParam('id', '');
		if ($id){
			$item = $users_model->find($id)->current();
			$this->layout->action_title = "Редактировать пользователя";	
		} else{
			$item = $users_model->createRow();
			$this->layout->action_title = "Создать пользователя";
		}
			
		if ($this->_request->isPost()){	

			$validators = $users_model->getValidators();
                        unset($validators['Условия регистрации']);
			$data = $this->_getParam('edit');
			$filter = FormFilter::getInstance()->getFilterInput($validators, $data);
			$deny_login = $users_model->checkField('login',$filter->getEscaped('login'), $id);
			$deny_email = $users_model->checkField('email',$filter->getEscaped('email'), $id);
			if ($filter->isValid() && !$deny_login && !$deny_email){
				$fields = $filter->getEscaped();
				$fields['login'] = $filter->getUnescaped('login');
				$fields['password'] = $filter->getUnescaped('password');				
				$item->setFromArray(array_intersect_key($fields, $item->toArray()));
				$item->save();
				$this->view->ok=1;
			} else {
				$errors =$filter->getMessages();
				$data = $this->_getParam('edit');
				$item->setFromArray(array_intersect_key($data, $item->toArray()));
				$ul_errors = array();
				foreach ((array)$errors as $key=>$err){
					$ul_errors[$key] = implode('<br>', $err);
				}
				if ($deny_email){
					$ul_errors['Email'] = "Такой email уже занят";
				}
				if ($deny_login){
					$ul_errors['Логин'] = "Такой логин уже занят";
				}
				$this->view->errors = $ul_errors;
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
			$page = SiteUsers::getInstance()->find($id)->current();
			
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
			$item = SiteUsers::getInstance()->find($id)->current();            
			$item->delete();			
			$this->_redirect($this->_curModule);
		}
	}
}