<?php

/**
 * Layout Helper
 * вывод хлебных крошек
 * 
 *
 */
class View_Helper_breadCumbs extends Zend_View_Helper_Abstract {
	
	/**
	 * @var Zend_View
	 */
	protected $_view = null;
	
	/**
	 * Enter description here...
	 *
	 */
	public function init() {
		
		$this->_view = Zend_Registry::get( 'view' );
	
	}
	
	/**
	 * Site menu
	 *
	 * @param int $type
	 * @param array $items
	 * @return phtml
	 */
	public function breadCumbs($id_page, $items = null) {
		$this->init();
		if ($id_page != '') {
			$page_row = Pages::getInstance()->find( $id_page )->current();
			$bread_cumbs = array ();
			while ( $page_row != null && $page_row->level != 0 ) {
				$bread_cumbs [] = array ('title' => $page_row->name, 'path' => $page_row->path );
				$page_row = $page_row->getParent();
			}
			$bread_cumbs = array_reverse( $bread_cumbs );
			
			if ($items != '' && sizeof( $items ) > 0 && $bread_cumbs) {
				$bread_cumbs = array_merge( $bread_cumbs, $items );
			}
			$bread_cumbs [count( $bread_cumbs ) - 1] ['end'] = '1';
			$this->_view->bread_cumbs = $bread_cumbs;
			return $this->_view->render( 'BreadCumbs.phtml' );
		}
	}
}
