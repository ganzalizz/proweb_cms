<?php
class Catalog_Product_Images_Row extends Zend_Db_Table_Row {
	
			
	public function toArray(){
		return parent::toArray();
	}
	/**
	 * delete
	 * @see application/library/Zend/Db/Table/Row/Zend_Db_Table_Row_Abstract#delete()
	 */
	public function delete(){	
		@unlink(DIR_PUBLIC."pics/catalog/product/".$this->img);		
		return parent::delete();
	}
	
	
	
	
}