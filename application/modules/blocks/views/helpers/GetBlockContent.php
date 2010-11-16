<?php

/**
 * Layout Helper
 *
 */
class View_Helper_getBlockContent extends Zend_View_Helper_Abstract
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
		
		//$this->_view = Zend_Registry::get('view');
	}
	
	public function getBlockContent($system_name, $order = null){
		$cache_blocks = Blocks_Cache::getInstance();
		return $cache_blocks->getContentBySystemName($system_name, $order);
		
	}	
}