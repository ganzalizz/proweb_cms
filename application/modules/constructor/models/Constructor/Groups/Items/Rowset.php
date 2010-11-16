<?php

class Constructor_Groups_Items_Rowset extends Zend_Db_Table_Rowset {

	/**
	 * @return array
	 */
	public function toArray(){
		return parent::toArray();
	}
	/**
	 * gets current row
	 *
	 * @return Catalog_Division_Row
	 */
	public function current(){
		return parent::current();
	}
	
}