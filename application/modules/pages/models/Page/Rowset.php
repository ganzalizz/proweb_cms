<?php

class Page_Rowset extends Zend_Db_Table_Rowset {

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
	/**
	 * gets array швы from rowste
	 * @return array
	 */
	public function getIdis(){
		$return=null;
		if (count($this)>0){
			foreach ($this as $row){
				$return[] = $row->id;
			}
		}
		return $return;
	}
	
}