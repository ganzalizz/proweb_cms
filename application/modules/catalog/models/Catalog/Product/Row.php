<?php
class Catalog_Product_Row extends Zend_Db_Table_Row {
	
	private $_default_options = array();
	
	public function getPrice(){
		return number_format($this->price, 2, '.', '');
	}
	
	public function getImgMain(){
		return  Catalog_Product_Images::getInstance()->fetchRow('active=1 AND id_tovar='.(int)$this->id, array('main DESC', 'priority DESC'));
	}
	
	public function getImages(){
		return  Catalog_Product_Images::getInstance()->fetchAll('active=1 AND id_tovar='.(int)$this->id, array('main DESC', 'priority DESC'));
	}
	
	public function getOptions(){
		return Catalog_Product_Options_Enabled::getCache()->getAllOptionsValues( $this->id );
	}
	
	/**
	 * получение параметров вместе со значениями
	 */
	public function getDefaultOptions(){
		$options = Catalog_Product_Default::getInstance()->getDefaultToProduct($this->id);
		
		if ($options) {
			foreach ($options as $key=>$option){
				$this->_default_options[$option->system_name]=$option;
			}
		}
		return $this->_default_options;
	}
	/**
	 * получение значения опции по system_name
	 * @param string $system_name
	 */
	public function getDefValue($system_name){
		if (!$this->_default_options){
			$this->_default_options = $this->getDefaultOptions();
		}
		if (isset($this->_default_options[$system_name])){
			$option = $this->_default_options[$system_name];
			return $option->value;
		}
		return '';
		
	}
	



    /**
     * каскадное удаление
     * удаляет все зависемости
     */
    public function delete() {

        Catalog_Product_Options_Prices::getInstance()->deletePricesByProduct($this->id);
		Catalog_Product_Options_Values::getInstance()->deleteByProduct($this->id);
		Catalog_Product_Options_Enabled::getInstance()->deleteByProduct($this->id);
		Catalog_Product_Images::getInstance()->deleteByProduct($this->id);
		Catalog_Product_Default_Values::getInstance()->deleteValues($this->id);
        /**
         * удаление элемента из индекса
         */
        Ext_Search_Lucene::deleteItemFromIndex ( $this->id, Ext_Search_Lucene::CATALOG_PRODUCTS );
        return parent::delete ();
    }

    /**
     * Allows post-update logic to be applied to row.
     * Subclasses may override this method.
     *
     * @return void
     */
    protected function _postUpdate() {
        Ext_Search_Lucene::deleteItemFromIndex ( $this->id, Ext_Search_Lucene::CATALOG_PRODUCTS );
        if ($this->active == 1) {
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
        if ($this->active == 1) {
            $this->addItemToSearchIndex();
        }
    }

    /**
     * добавление элемента в индекс
     * @return void
     *
     */
    protected function addItemToSearchIndex() {
        $index = Ext_Search_Lucene::open ( Ext_Search_Lucene::CATALOG_PRODUCTS );
        $doc = new Ext_Search_Lucene_Document ( );
        $doc->setUrl (SiteDivisionsType::getInstance()->getPagePathBySystemName('division').'/product/'.$this->id);
        $doc->setTitle ( $this->title );
        $doc->setContent ( strip_tags ( $this->description ) );
        $doc->setId ( $this->id );
        $index->addDocument ( $doc );
    }

}