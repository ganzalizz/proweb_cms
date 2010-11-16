<?php

class Images extends Zend_Db_Table {
	
	protected $_name = 'site_printed_images';
	protected $_primary = array('id');
	protected $_sequence = true; // Использование таблицы с автоинкрементным ключом
	protected static $_instance = null;
	
	/**
	 * Class to use for row	
	 *
	 * @var string
	 */
	protected $_rowClass = "Images_Row" ;
	
	
	protected $_dependentTables = array(
		
	);
	
	protected $_referenceMap    = array(       
        'tovar'=> array(
            'columns'           => 'id_printed',
            'refTableClass'     => 'Printed',
            'refColumns'        => 'id',
	 		'onDelete'          => self::CASCADE,
        ),
    );
	
	/**
	 * Singleton instance
	 *
	 * @return Catalog_Tovar_Images
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}