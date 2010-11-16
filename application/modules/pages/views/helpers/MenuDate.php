<?php

/**
 * Layout Helper
 * вывод хлебных крошек
 * 
 *
 */
class View_Helper_menuDate extends Zend_View_Helper_Abstract
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
    public function MenuDate()
    {
    	$this->init();
        $date = new Zend_Date();
        $this->_view->date = $date->toString("dd.MM.YYYY");
        return $this->_view->render('MenuDate.phtml') ;
    }
}
