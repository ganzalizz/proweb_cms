<?php

class Catalog_Division_Row extends Zend_Db_Table_Row {
	
	public function toArray() {
		return parent::toArray();
	}
	
	/**
	 * Получение  дочерних элементов раздела
	 *
	 *@param int $this->id
	 *@return Catalog_Division_Rowset
	 */
	
	public function getChilds($count = null, $offset = null) {
		return Catalog_Division::getInstance()->fetchAll( 'parent_id =' . $this->id, 'sortid', $count, $offset );
	
	}
	
	/**
	 * проверяет наличее детей в разделе
	 *
	 * @param int $this->id
	 *
	 */
	
	public function issetChilds() {
		$res = Catalog_Division::getInstance()->fetchRow( 'parent_id =' . $this->id );
		if ($res != null) {
			return true;
		}
		return false;
	
	}
	/**
	 * проверяет наличие товаров в разделе
	 * @return bollean
	 */
	public function issetProductInDiv() {
		return Catalog_Product::getInstance()->issetProductInDiv( $this->id );
	
	}
	/**
	 * gets tovar from this division
	 * return Catalog_Product_Rowset
	 */
	public function getProducts($count = null, $offset = null) {
		return Catalog_Product::getInstance()->getAllProductsByDivId( $this->id, $offset, $count );
	}
	
	/**
	 * Получение количества дочерних элементов раздела
	 *
	 * @param int $this->id
	 *
	 */
	
	public function getCountOfChildren() {
		
		$count = count( $this->getChilds( $this->id ) );
		
		return $count;
	
	}
	
	public function getParent() {
		return Catalog_Division::getInstance()->find( $this->parent_id )->current();
	}
	
	/**
	 * каскадное удаление
	 * удаляет все вложенные разделы вместе с товарами
	 */
	public function delete() {
		
		if ($this->issetChilds()) {
			$childs = $this->getChilds();
			foreach ( $childs as $node ) {
				$node->delete();
			}
		}
		@unlink( DIR_PUBLIC . "pics/catalog/division/" . $this->img );
		/**
		 * удаление элемента из индекса
		 */
		Ext_Search_Lucene::deleteItemFromIndex( $this->id, Ext_Search_Lucene::CATALOG_DIVISIONS );
		return parent::delete();
	}
	
	/**
	 * хлебные крошки
	 * @param string $path
	 * @return array
	 */
	public function getBread($path) {
		$bread [] = array ('title' => $this->name, 'path' => $path . "/division/" . $this->id );
		$current = $this;
		while ( $parent = $current->getParent() ) {
			$bread [] = array ('title' => $parent->name, 'path' => $path . "/division/" . $parent->id );
			$current = $parent;
		}
		return array_reverse( $bread );
	}
	
	/**
	 * Allows post-update logic to be applied to row.
	 * Subclasses may override this method.
	 *
	 * @return void
	 */
	protected function _postUpdate() {
		Ext_Search_Lucene::deleteItemFromIndex( $this->id, Ext_Search_Lucene::CATALOG_DIVISIONS );
		$this->addItemToSearchIndex();
	
	}
	
	/**
	 * Allows post-insert logic to be applied to row.
	 * Subclasses may override this method.
	 *
	 * @return void
	 */
	protected function _postInsert() {
		$this->addItemToSearchIndex();
	}
	
	/**
	 * добавление элемента в индекс
	 * @return void
	 *
	 */
	protected function addItemToSearchIndex() {
		$index = Ext_Search_Lucene::open( Ext_Search_Lucene::CATALOG_DIVISIONS );
		$doc = new Ext_Search_Lucene_Document( );
		$doc->setUrl( SiteDivisionsType::getInstance()->getPagePathBySystemName( 'division' ) . '/division/' . $this->id );
		$doc->setTitle( $this->name );
		$doc->setContent( strip_tags( $this->description ) );
		$doc->setId( $this->id );
		$index->addDocument( $doc );
	}

}