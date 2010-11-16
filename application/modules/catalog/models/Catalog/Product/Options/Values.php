<?php
/**
 * Catalog_Tovar
 *
 */
class Catalog_Product_Options_Values extends Zend_Db_Table {
	
	
	
	
	/**
	 * The default table name.
	 *
	 * @var string
	 */
	protected $_name = 'site_catalog_product_options_values';
	
	/**
	 * The default primary key.
	 *
	 * @var array
	 */
	protected $_primary = array('id');
	
	/**
	 * Whether to use Autoincrement primary key.
	 *
	 * @var boolean
	 */
	protected $_sequence = true; // Использование таблицы с автоинкрементным ключом
	
	/**
	 * Singleton instance.
	 *
	 * @var St_Model_Layout_Pages
	 */
	protected static $_instance = null;
	
	/**
	 * Dependent tables.
	 *
	 * @var array
	 */
	/*protected $_dependentTables = array(
		
		'Catalog_Product_Images'
		
	) ;*/

	/**
	 * Reference map.
	 *
	 * @var array
	 */
	/*protected $_referenceMap = array(
		'Catalog_Division' => array(
            'columns'           => array('id_division'),
            'refTableClass'     => 'Catalog_Division',
            'refColumns'        => array('id'),
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::RESTRICT
        )
	) ;*/

	
	/**
	 * Class to use for rows.
	 * Class to use for rows.
	 *
	 * @var string
	 */
	//protected $_rowClass = "Catalog_Product_Row" ;
	
	/**
	 * Class to use for row sets.
	 *
	 * @var string
	 */
	//protected $_rowsetClass = "Catalog_Product_Rowset" ;
	
	
	
	
	/**
	 * Singleton instance
	 *
	 * @return Catalog_Product_Options_Values
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	/**
	 * получить значения опции для товара
	 * @param int $id_product
	 * @param int $id_option
	 * @return Zend_Db_Table_Rowset
	 */
	public function getProductOptionValues($id_product, $id_option) {
		$select = $this->select();
		$select->from(array('v'=>$this->_name), 'v.*');
		$select->setIntegrityCheck(false);
		$select->joinInner(
				array('en'=>'site_catalog_product_options_enabled'), 
				'en.id_option=v.id_option AND en.id_product=v.id_product', 
				array()
					
		);
		
		$select->joinLeft(
			array('p'=>'site_catalog_product_options_prices'),
			'p.id_value=v.id', 
			array('p.price')
		);		
		$select->where("v.id_product = ?", $id_product);		
		$select->where("v.id_option = ?", $id_option);
		$select->group("v.id");	
		$select->order("v.priority DESC");		
		return $this->fetchAll($select);
				
		
	}
	
/**
	 * получить все значения опций для товара
	 * @param int $id_product
	 * @param array $options_ids
	 * @return Zend_Db_Table_Rowset
	 */
	public function getAllProductOptionsValues($id_product, array $options_ids) {
		$select = $this->select();
		$select->from(array('v'=>$this->_name), 'v.*');
		$select->setIntegrityCheck(false);
		$select->joinInner(
				array('en'=>'site_catalog_product_options_enabled'), 
				'en.id_option=v.id_option AND en.id_product=v.id_product', 
				array()
					
		);
		
		$select->joinLeft(
			array('p'=>'site_catalog_product_options_prices'),
			'p.id_value=v.id', 
			array('p.price', 'p.id as id_price')
		);		
		$select->where("v.id_product = ?", $id_product);		
		$select->where("v.id_option IN (".implode(',',$options_ids ).") ", null);
		$select->group("v.id");	
		$select->order("v.priority DESC");		
		return $this->fetchAll($select);
				
		
	}
	
	/**
	 * получение значения опции вместе с ценой
	 * @param int $id
	 * @return Zend_Db_Table_Row
	 * 
	 */
	public function getOptionValue($id){
		$select = $this->select();
		$select->from(array('v'=>$this->_name), 'v.*');
		$select->setIntegrityCheck(false);
		$select->joinLeft(
			array('p'=>'site_catalog_product_options_prices'),
			'p.id_value=v.id', 
			array('p.price')
		);		
		$select->where("v.id = ?", $id);
		return $this->fetchRow($select);
	}
	
/**
	 * удаление связей при удалении товара
	 * @param int $id_product
	 */
	public function deleteByProduct($id_product){
		$where = $this->getAdapter()->quoteInto("id_product = ?", (int)$id_product);
		return $this->delete($where);
	}
	/**
	 * удаление связей при удалении опции
	 * @param int $id_option
	 */
	public function deleteByOption($id_option){
		$where = $this->getAdapter()->quoteInto("id_option = ?", (int)$id_option);
		return $this->delete($where);
	}
	
	/**
	 * получаем название опции по id стоимости
	 * @param int $id_price
	 * @return string
	 */
	public function getTitleByPriceId($id_price){
		$select = $this->select();
		$select->setIntegrityCheck(false);
		$select->from(array('v'=>$this->_name), 'v.title');
		$select->joinInner(array('p'=>'site_catalog_product_options_prices'), 'p.id_value=v.id', array());
		$select->where("p.id = ?", (int)$id_price);
		return $this->getAdapter()->fetchOne($select);
		
	}
	
	
		
}