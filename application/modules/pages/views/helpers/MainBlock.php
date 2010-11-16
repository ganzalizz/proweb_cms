<?php

/**
 * Layout Helper
 * вывод хлебных крошек
 * 
 *
 */
class View_Helper_mainBlock extends Zend_View_Helper_Abstract
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
	 * Блок с ножом на главной странице
	 *
	 * @return phtml
	 */
    public function mainBlock()
    {
    	$this->init();     	
        return $this->_view->render('MainBlock.phtml') ;
    }
}
