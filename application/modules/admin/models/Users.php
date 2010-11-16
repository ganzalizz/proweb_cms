<?php
class Users extends Zend_Db_Table 
{
	protected $_name = 'users';
	protected $_primary = array('id');
	
	protected static $_instance = null;
	
	/**
	 * Валидаторы
	 *
	 * @var array
	 */
	protected $_validators = array(
            'Роль' => array(
	        'presence'=>'required',  // обязательный параметр
			Zend_Filter_Input::FIELDS =>'role',
	    ),
	    'Имя' => array( 
	        'presence'=>'required',  // обязательный параметр		
			Zend_Filter_Input::FIELDS =>'firstName',	
	    ),   
	    'Фамилия'=>array(
	    	 'allowEmpty' => true,  // не обязательный параметр
	    	 Zend_Filter_Input::FIELDS =>'lastName',
	    ),
	    'Логин'=>array(
	    	 'presence'=>'required',  // обязательный параметр	
	    	 Zend_Filter_Input::FIELDS =>'username',
	    ),	
	    'Пароль' => array(	        
	        'presence'=>'required',  //обязательный параметр
	    	Zend_Filter_Input::FIELDS =>'password'
	    ),	        
	       
	    'Email' => array(
	        'EmailAddress',
	        'presence'=>'required',  //обязательный параметр
	    	Zend_Filter_Input::FIELDS =>'email'
	    		    		    	
	    ),	   
	    
	    
	    'Активен'=>array(
	    	  'allowEmpty' => true,  // не обязательный параметр
	    	 // 'default'=>0,
	    	  Zend_Filter_Input::FIELDS =>'activity'		
	    ),
	    'Получать уведомления'=>array(
	    	  'allowEmpty' => true,  // не обязательный параметр
	    	  //'default'=>0,
	    	  Zend_Filter_Input::FIELDS =>'send_message'		
	    )	    
	    
	);
	
	
	/**
	 * Singleton instance
	 * @return Users
	 */
	public static function getInstance()
	{
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	/**
	 * валидаторы
	 *
	 * @return array
	 */
	public function getValidators(){
		return $this->_validators;
	}
	
	
	public function getUsers(){
		$cur_user = Security::getInstance()->getUser();
		if ($cur_user!=null && $cur_user->deletable!=0){
			$where = $this->getAdapter()->quoteInto('deletable = ?', '1');
		} else {
			$where = null;
		}
		return $this->fetchAll($where);
	}
	
	public function setActivity($id, $value){
		$where = $this->getAdapter()->quoteInto('id = ?', $id);
		$this->update(array('activity' => $value), $where);
		
		return true;
	}
	
	public function deleteUser($id){
		$where = $this->getAdapter()->quoteInto('id = ?', $id);
		$this->delete($where);
		
		return true;
	}
	
	public function editUser($id, $data){
		$where = $this->getAdapter()->quoteInto('id = ?', $id);
//		print_r($data);exit;
		$data = array(
			'username' => $data['username'],
			'password' => $data['password'],
			'email' => $data['email'],
			'firstName' => $data['first_name'],
			'lastName' => $data['last_name'],
			'activity' => isset($data['activity']) ? '1' : '0'
			);
		$this->update($data, $where);
		
		return true;
	}
	
	public function getUser($id){
		$where = $this->getAdapter()->quoteInto('id = ?', $id);
		$user = $this->fetchRow($where);
		
		return $user;
	}
	
	public function addUser($data){
		$data = array(
			'username' => $data['username'],
			'password' => $data['password'],
			'email' => $data['email'],
			'firstName' => $data['first_name'],
			'lastName' => $data['last_name'],
			'deletable' => '1',
			'role' => 'admin',
			'activity' => isset($data['activity']) ? '1' : '0'
			);
		$this->insert($data);
		
		return true;
	}

    public function getUsersToSendMail() {
        $where = $this->getAdapter ()->quoteInto ( "send_message = ?", 1 );

		return $this->fetchAll ( $where );
    }
    
    /**
     * получение юзеров по роли 
     * @param string $role
     * @return Zend_Db_Table_Rowset       
     */
    public function getUsersByRole($role){
    	$select = $this->select();
    	$select->where("role= ?", $role);
    	$select->order("firstName");
    	return $this->fetchAll($select);
    }
	
}