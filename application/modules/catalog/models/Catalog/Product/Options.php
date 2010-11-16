<?php
/**
 * Catalog_Tovar
 *
 */
class Catalog_Product_Options extends Zend_Db_Table {
	
	
	
	
	/**
	 * The default table name.
	 *
	 * @var string
	 */
	protected $_name = 'site_catalog_product_options';
	
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
	 * @return Catalog_Product_Options
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	/**
	 * добавление элементов
	 * @param array $data
	 */
	public function AddItems($data){
		if (is_array($data)){
			foreach ($data as $title){
				if ($title!=''){
					$row = $this->fetchNew();
					$row->title = $title;
					$row->save();
				}
			}
		}
	}
	
	/**
	 * действия над элементами
	 * @param string $action
	 * @param array $ids
	 */
	public function processItems($action, $ids){				
		$rowset = $this->find($ids);
		foreach ($rowset as $row){
			switch ($action) {				
				case 'delete': $row->delete();
				;
				break;
				
				
			}
		}
	}
	
	/**
	 * получить список параметров не назначенных товару
	 * @param int $id_product
	 * @return array
	 * 
	 */
	public function getOptionsToProduct($id_product){
		$sql = "SELECT *
				FROM $this->_name AS opt
				WHERE opt.id NOT IN(SELECT
				                      en.id_option
				                    FROM site_catalog_product_options_enabled AS en
				                    WHERE en.id_product = $id_product)";
		return $this->getAdapter()->fetchAll($sql);
	}
	
	
		
}