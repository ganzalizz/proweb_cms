<?php
class Roles extends Zend_Db_Table
{
	protected $_name = 'roles';
	protected $_primary = array('id');
	
	protected static $_instance = null;
	
	/**
	 * Валидаторы
	 *
	 * @var array
	 */
	protected $_validators = array(
	    'Системное имя' => array(
	        'presence'=>'required',  // обязательный параметр		
			Zend_Filter_Input::FIELDS =>'name',
	    ),   
	    'Название'=>array(
	    	 'presence'=>'required',  // не обязательный параметр
	    	 Zend_Filter_Input::FIELDS =>'title',
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
	
	
	public function getRolesForSelect(){
            $roles = $this->fetchAll();
            $result = array();
            foreach ($roles as $role) {
                $result[$role['name']] = $role['title'];
            }
            return $result;
	}
}