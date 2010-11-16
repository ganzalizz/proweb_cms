<?php

/**
 * Layout Helper
 * вывод хлебных крошек
 * 
 *
 */
class View_Helper_mainPopular extends Zend_View_Helper_Abstract
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
    public function mainPopular()
    {
    	$this->init();
        $this->_view->products = Catalog_Product::getInstance()->getPopular();
        $this->_view->path = SiteDivisionsType::getInstance()->getPagePathBySystemName('division');
        return $this->_view->render('MainPopular.phtml') ;
    }
}
