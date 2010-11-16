<?php
/**
 * Catalog_Tovar
 *
 */
class Catalog_Product_Default extends Zend_Db_Table {
	
	
	
	
	/**
	 * The default table name.
	 *
	 * @var string
	 */
	protected $_name = 'site_catalog_product_default';
	
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
	
	
	private $_types = array(
		'input'		=> 'Текстовое поле',		
		'textarea' 	=> 'Многострочное текстовое поле',
		'checkbox' 	=> 'Флажок ',
		'fck'		=> 'Html редактор',	
		'fck_small'	=> 'Html редактор мини'	
	);
	
	
	/**
	 * Singleton instance
	 *
	 * @return Catalog_Product_Default
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	/**
	 * @return array 
	 */
	public function getTypes(){
		return $this->_types;
	}
	
	
	
	public function getDefaultToProduct($id_product=null){
		$select = $this->select();
		$select->setIntegrityCheck(false);
		$select->from(
			array('default'=>$this->_name), 
			array(
				'default.*',
				// для новых товаров
				// если значения нет, подставляется дефолтное
				'IF(values.id_product>0, values.value, default.default_value) AS value'
			)
		);	
		$select->distinct(true);
		$select->joinLeft(
				array('values'=>'site_catalog_product_default_values'),
				"values.id_default=default.id AND values.id_product='".(int)$id_product."'", 
				array('values.id_product' )
			);
		if ($id_product){	
			
			$select->where('values.id_product = ?', $id_product);
		}	
		$select->order("default.priority DESC");			
		return $this->fetchAll($select);
	}
	
	
		
}