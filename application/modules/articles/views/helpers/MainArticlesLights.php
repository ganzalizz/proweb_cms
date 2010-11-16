<?php

/**
 * Layout Helper
 * вывод новостей на главной
 */
class View_Helper_mainArticlesLights extends Zend_View_Helper_Abstract {

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
     * Статьи в светофоре на главной странице
     *
     * @return phtml
     */
    public function mainArticlesLights() {
        $this->init();
        $this->view->mainarticleslights = Articles::getInstance()->getTrafficLightingByColor();
        return $this->_view->render('mainarticleslights.phtml') ;
    }
}
