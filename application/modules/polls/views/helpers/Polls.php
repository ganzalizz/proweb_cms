<?php

/**
 * Layout Helper
 * вывод хлебных крошек
 *
 *
 */
class View_Helper_Polls extends Zend_View_Helper_Abstract
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
    public function Polls()
    {
    	$this->init();
        $this->view->poll = Polls::getInstance()->getPoll();
        $this->view->answers = Pollsitems::getInstance()->getActiveItemsByParentId($this->view->poll[0]->id);
        return $this->_view->render( 'polls/Polls.phtml' );
    }
}
