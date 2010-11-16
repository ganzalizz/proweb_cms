<?php

class Otzivy_Rowset extends Zend_Db_Table_Rowset {

	/**
	 * @return array
	 */
	public function toArray(){
		return parent::toArray();
	}
	/**
	 * gets current row
	 *
	 * @return News_Row
	 */
	public function current(){
		return parent::current();
	}
	
	
}