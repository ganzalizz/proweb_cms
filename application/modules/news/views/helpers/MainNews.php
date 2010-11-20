<?php

/**
 * Layout Helper
 * вывод новостей на главной
 */
class View_Helper_mainNews extends Zend_View_Helper_Abstract {

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
     * Новости на главной странице
     *
     * @return phtml
     */
    public function mainNews() {
        $this->init();
        $this->view->mainnews = News::getInstance()->getIsMain();
        return $this->_view->render('mainnews.phtml') ;
    }
}
