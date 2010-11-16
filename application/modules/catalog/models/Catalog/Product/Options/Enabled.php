<?php
/**
 * Catalog_Tovar
 *
 */
class Catalog_Product_Options_Enabled extends Zend_Db_Table {
	
	
	
	
	/**
	 * The default table name.
	 *
	 * @var string
	 */
	protected $_name = 'site_catalog_product_options_enabled';
	
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
	 * @return Catalog_Product_Options_Enabled
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	public static function getCache(){
		return Catalog_Product_Options_Enabled_Cache::getInstance();
	}
	
	/**
	 * получение параметров добавленыых к товару
	 * @param int $id_product
	 * @param int $count
	 * @param int $offset
	 * @return Zend_Db_Table_Rowset
	 */
	public function  getProductOptions($id_product, $count = null, $offset = null){
		$select = $this->select();
		$select->setIntegrityCheck(false);
		$select->from(array('en'=>$this->_name), array('*', 'opt.title'));		
		$select->joinInner(array('opt'=>'site_catalog_product_options'), 'en.id_option = opt.id', array('title'));
		$select->where("id_product = ?", $id_product);
		$select->order("priority DESC");
		$select->limit($count, $offset);
		return $this->fetchAll($select);
	}
	/**
	 * получение опции
	 * @param int $id_product
	 * @param int $id_option
	 * @return Zend_Db_Table_Row
	 */
	public function getOption($id_product, $id_option){
		$select = $this->select();
		$select->where("id_product = ?", $id_product);
		$select->where("id_option = ?", $id_option);
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
	 * 
	 * @param int $id_product
	 * @return array
	 * 
	 */
	public function getAllOptionsValues($id_product){
		$options = $this->getProductOptions($id_product);
		$ids = array();
		$array_options = array();
		if ($options->count()) {
			foreach ($options as $option){
				if (!in_array($option['id_option'], $ids)){
					$ids[] = $option['id_option'];
				}
				$array_options[$option['id_option']] = $option->toArray();
			}			
			$values = Catalog_Product_Options_Values::getInstance()->getAllProductOptionsValues($id_product, $ids);
						
			foreach ($values as $value){
				$array_options[$value->id_option]['values'][] = $value->toArray();
			}
			
			foreach ($array_options as $key => $option){
				if (!isset($option['values'])){
					unset($array_options[$key]);
				}
			}
			
		}
		return $array_options;
		
	}
	
	
	
	
		
}