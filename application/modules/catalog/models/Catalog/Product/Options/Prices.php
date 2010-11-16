<?php
/**
 * Catalog_Product_Options_Prices
 *
 */
class Catalog_Product_Options_Prices extends Zend_Db_Table {
	
	
	
	
	/**
	 * The default table name.
	 *
	 * @var string
	 */
	protected $_name = 'site_catalog_product_options_prices';
	
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
	 * @return Catalog_Product_Options_Prices
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	
	
	/**
	 * получение получение цены по id значения
	 * @param int $id_value
	 * @return Zend_Db_Table_Row
	 * 
	 */
	public function getValuePrice($id_value= null){
		$select = $this->select();		
		$select->where("id_value= ?", (int)$id_value);
		$row = $this->fetchRow($select);
		if ($row!=null){
			return $row->price;
		}
		return 0;
	}
	
	/**
	 * обновление/добавление цены к значению опции
	 * @param int $id_value
	 * @param int $id_product
	 * @param int $id_option
	 * @param int $price
	 * @return int
	 * 
	 */
	public function updateValuePrice($id_value, $id_product, $id_option, $price){
		$data = array(
			'id_product'=>$id_product,
			'id_option'=>$id_option,
			'id_value'=>$id_value,
			'price' => $price
		);
		$select = $this->select();
		$select->where("id_value = ?", (int)$id_value);
		$row = $this->fetchRow($select);
		if (is_null($row)){
			$row = $this->fetchNew();
		}
		$row->setFromArray($data);
		return $row->save();
		
	}
	
	/**
	 * удаление цены
	 * @param int $id_value
	 */
	public function deleteValuePrice($id_value){
		$where = $this->getAdapter()->quoteInto("id_value = ?", (int)$id_value);
		return $this->delete($where);
	}
	/**
	 * удаление цен при удалении товара
	 * @param unknown_type $id_product
	 */
	public function deletePricesByProduct($id_product){
		$where = $this->getAdapter()->quoteInto("id_product = ?", (int)$id_product);
		return $this->delete($where);
	}
	
/**
	 * удаление цен при удалении опции
	 * @param unknown_type $id_product
	 */
	public function deletePricesByOption($id_option){
		$where = $this->getAdapter()->quoteInto("id_option = ?", (int)$id_option);
		return $this->delete($where);
	}
	/**
	 * получение цены по id
	 * @param int $id_price
	 * @param int $id_product
	 */
	public function getPriceById($id_price, $id_product){
		$select = $this->select();
		$select->where("id = ?", (int)$id_price);
		$select->where("id_product = ?", (int)$id_product);
		$row = $this->fetchRow($select);
		if ($row!=null){
			return $row->price;
		}
		return null;
		
	}
	
	
		
}