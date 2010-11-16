<?php
/**
 * Catalog_Tovar
 *
 */
class Catalog_Product_Default_Values extends Zend_Db_Table {
	
	
	
	
	/**
	 * The default table name.
	 *
	 * @var string
	 */
	protected $_name = 'site_catalog_product_default_values';
	
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
	//protected $_rowClass = "Catalog_Product_Default_Values_Row" ;
	
	/**
	 * Class to use for row sets.
	 *
	 * @var string
	 */
	//protected $_rowsetClass = "Catalog_Product_Rowset" ;
	
	
	
	
	/**
	 * Singleton instance
	 *
	 * @return Catalog_Product_Default_Values
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	/**
	 * при создании новой опции добавляет значения всем товарам
	 * @param Zend_Db_Table_Row $option
	 */
	public function addNewValues($option){
		$product_ids = $this->getAdapter()->fetchCol("SELECT DISTINCT id FROM site_catalog_product");		
		if ($product_ids){
			foreach ($product_ids as $id_product){
				$item 				= $this->fetchNew();
				$item->id_default 	= $option->id;
				$item->id_product 	= $id_product;
				$item->value 		= $option->default_value;
				$item->save();
			}
		}
	}
	
	/**
	 * обновление/создание  значений для товара
	 * @param int $id_product
	 * @param array $options
	 */
	public function updateValues($id_product, $options){		
		if ($id_product && is_array($options)){
			foreach ($options as $id_default=>$value){				
				$value_row = $this->getOptionValue($id_product, $id_default);
				$value_row->value = $value;
				$value_row->save();			
			}
		}
	}
	
	/**
	 * получить/создать значение для опции
	 * @param int $id_product
	 * @param int $id_default
	 * @return Zend_Db_Table_Row
	 */
	public function getOptionValue($id_product, $id_default){
		$select = $this->select();
		$select->where('id_product = ?', $id_product);
		$select->where('id_default =?', $id_default);
		$value = $this->fetchRow($select);
		if ($value==null){
			$value = $this->createRow(
				array(
					'id_product'	=> (int)$id_product,
					'id_default'	=> (int)$id_default
				)
			);
			
		}		
		return $value;
	}
	
	/**
	 * удаление значений при удалении товара или опции
	 * @param int $id_product
	 * @param int $id_default
	 * @return int
	 */
	public function deleteValues($id_product = null, $id_default = null){
		$where = array();
		if ($id_default ){
			$where[] = $this->getAdapter()->quoteInto("id_default = ?", $id_default);
		}
		if ($id_product) {
			$where[] = $this->getAdapter()->quoteInto("id_product = ?", $id_product); 
		}
		return $this->delete($where);
	}
	
	
	
	
		
}