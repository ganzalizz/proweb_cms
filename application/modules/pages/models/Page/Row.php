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
		if (count ( Pages::getInstance ()->fetchAll ( 'parentId=' . ( int ) $this->id . ' AND published=1 ' ) ) > 0) {
			return true;
		} else
			return false;
	}
	public function getChild($count = 0) {
		return Pages::getInstance ()->fetchAll ( 'parentId=' . $this->id . ' AND published=1 ', 'sortId ASC', $count );
	}
	
	public function getRightMenu() {
		$id_uslugi_page = 172;
		$html = '';
		if ($this->id != $id_uslugi_page && $this->parentId != $id_uslugi_page) {
			$childs = $this->getChild ();
			if (sizeof ( $childs ) > 0) {
				$html .= '<ul class="right_menu">';
				foreach ( $childs as $child ) {
					$html .= "<li><a href='/$child->path'>$child->name</a></li>\r\n";
				}
				$html .= '</ul>';
			}
		
		}
		return $html;
	}
	
	public function delete() {
		/**
		 * удаление элемента из индекса
		 */
		Ext_Search_Lucene::deleteItemFromIndex ( $this->id, Ext_Search_Lucene::PAGES );
		return parent::delete ();
	}
	
	/**
	 * Allows post-update logic to be applied to row.
	 * Subclasses may override this method.
	 *
	 * @return void
	 */
	protected function _postUpdate() {
		Ext_Search_Lucene::deleteItemFromIndex ( $this->id, Ext_Search_Lucene::PAGES );
		if ($this->published == 1) {
			$this->addItemToSearchIndex();
		}
	
	}
	
	/**
	 * Allows post-insert logic to be applied to row.
	 * Subclasses may override this method.
	 *
	 * @return void
	 */
	protected function _postInsert() {
		/**
		 * индексируются только опубликованные элементы
		 */
		if ($this->published == 1) {
			$this->addItemToSearchIndex();
		}
	}
	
	/**
	 * добавление элемента в индекс
	 * @return void
	 * 
	 */
	protected function addItemToSearchIndex(){
		$index = Ext_Search_Lucene::open ( Ext_Search_Lucene::PAGES );
			$doc = new Ext_Search_Lucene_Document ( );			
			$doc->setUrl ( $this->path );
			$doc->setTitle ( $this->name );
			$doc->setContent ( strip_tags ( $this->content ) );
			$doc->setId ( $this->id );
			$index->addDocument ( $doc );
	}
}