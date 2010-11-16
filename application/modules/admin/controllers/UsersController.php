<?php

class Admin_UsersController extends MainAdminController
{
	
	/**
	 * @var Zend_Layout
	 */
	protected $layout = null ;
	/**
	 * языковая версия 
	 *
	 * @var string
	 */
	protected $_lang = null;
	/**
	 * ссылка на index action 
	 *
	 * @var unknown_type
	 */
	protected $_currentModule = null;
	/**
	 * название в админке
	 *
	 * @var string
	 */
	protected $_title = "Управление пользователями";
	/**
	 * Users
	 *
	 * @var Users object
	 */
	protected $_users_model = null;
	
	public function init(){		
		$this->layout = $this->view->layout() ;
		//$this->view = $this->layout->getView();
		$this->_lang = $this->_getParam('lang', 'ru');
		$request = $this->getRequest();
		$this->_users_model = Users::getInstance();
		$this->_currentModule = "/".$request->getModuleName()."/".$this->_lang."/".$request->getControllerName()."/";
		$this->view->currentModule = $this->_currentModule;
		$this->layout->title = $this->_title; 
		
		
		//echo $this->_currentModule;
		//echo  $this->getRequest()->getActionName();
		//echo $this->getRequest()->getControllerName();
		
		
		
	}
	
	public function indexAction(){
		$this->layout->action_title = "Список пользователей";
		$this->view->users = $users = $this->_users_model->getUsers();
		
	}
	
	public function usersListAllAction(){
		
	}
	
	public function activeAction(){
		if($this->_hasParam('id')){
			$id = $this->_getParam('id');
			$user =$this->_users_model->find($id)->current();
			if (!is_null($user)){
				if ($user->activity==0){
					$user->activity=1;
				} else{
					$user->activity=0;
				}
				$user->save();
			}
		}	
		
		//$lang = $this->_hasParam('lang') ? $this->getParam('lang') : 'ru';
		$this->_redirect($this->_currentModule);		
	}
	
	
	
	public function deleteAction(){
		if($this->_hasParam('id'))
			Users::getInstance()->deleteUser($this->_request->getParam('id'));
			
		$lang = $this->_hasParam('lang') ? $this->getParam('lang') : 'ru';
		$this->_redirect($this->_currentModule);		
	}
	
	public function editAction(){
		$id = $this->_getParam('id',0);
		$user = $this->_users_model->find($id)->current();
		if (is_null($user)){
			$user = $this->_users_model->fetchNew();
		}
		if ($this->_request->isPost()){
			$validators = $this->_users_model->getValidators();
                        
			$filter = $this->getFilterInput($validators);
			if ($filter->isValid()){
				$data = $filter->getEscaped();				
				$data['login'] = $filter->getUnescaped('login');
				$data['password'] = $filter->getUnescaped('password');
				$data['deletable'] = '1';
				$data = array_intersect_key($data, $user->toArray());
				$user->setFromArray($data);
				$user->save();
				$this->view->ok = 1;
				$this->_redirect($this->_currentModule);	
			} else {
				$this->view->errors = $filter->getMessages();
			}
			
		}
		
		
		$this->view->item = $user;
                $this->view->roles = Roles::getInstance()->getRolesForSelect();
	}
}
	