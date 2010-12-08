<?php

class Page_Row extends Zend_Db_Table_Row {

	/**
	 *  @var Zend_view
	 */
	protected $_view = null;
	
	public function init() {
		$this->_view = Zend_Registry::get ( 'view' );
	}
	/**
	 * gets route to page
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}
	
	public function getParent() {
		return Pages::getInstance ()->find ( $this->parentId )->current ();
	}
	
	public function issetChild() {
		if (count ( Pages::getInstance ()->fetchAll ( 'parentId=' . ( int ) $this->id . ' AND is_active=1 ' ) ) > 0) {
			return true;
		} else
			return false;
	}
	public function getChild($count = 0) {
		return Pages::getInstance ()->fetchAll ( 'parentId=' . $this->id . ' AND is_active=1 ', 'sortId ASC', $count );
	}
	
	public function getRightMenu() {
		$id_uslugi_page = 172;
		$html = '';
		if ($this->id != $id_uslugi_page && $this->parentId != $id_uslugi_page) {
			$childs = $this->getChild ();
			if (sizeof ( $childs ) > 0) {
				$html .= '<ul class="right_menu">';
				foreach ( $childs as $child ) {
					$html .= "<li><a href='/$child->path'>$child->title</a></li>\r\n";
				}
				$html .= '</ul>';
			}
		
		}
		return $html;
	}
	
	
}