<?php

/**
 * Layout Helper
 * вывод хлебных крошек
 * 
 *
 */
class View_Helper_userArea extends Zend_View_Helper_Abstract
{
	
	/**
	 * @var Zend_View
	 */
	protected $_view = null ;
	
	/**
	 * Enter description here...
	 *
	 */
	public function init() {
		
		$this->_view = Zend_Registry::get('view');
		
		
	}
	
	/**
	 * Site menu
	 *
	 * @param int $type
	 * @param array $items
	 * @return phtml
	 */
    public function userArea()
    {
    	$this->init();
    	if (SiteAuth::getInstance()->getIdentity()){
    		$user = SiteAuth::getInstance()->getUser();  
    		if (!is_null($user)){
                        
    			$this->_view->name = $user->first_name;
    			$this->_view->id_user = $user->id;    			
    		}
                
    	}
        else if (isset($_COOKIE['user_id']) && $_COOKIE['user_id']){
            $user = SiteUsers::getInstance()->fetchRow("id=".(int)$_COOKIE['user_id']);
            $this->_view->name = $user->first_name;
            $this->_view->id_user = $user->id;
        }
//        var_dump($_SESSION);
//        var_dump($_COOKIE);
			return $this->_view->render( 'users/UserArea.phtml' ) ;
    	
    }
}
