<?php

require_once('Zend/Db/Table.php');

class MenuTypes extends Zend_Db_Table {
	protected $_name = 'site_menu_types';
	protected $_primary = array('id');
	protected static $_instance = null;

	/**
	 * Singleton instance
	 *
	 * @return MenuTypes
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Получение типа меню (гор., верт...)
	 *
	 * @param string $name
	 * @return object
	 */
	public function getType($name){
		$where = $this->getAdapter()->quoteInto('name = ?', $name);
		$result = $this->fetchRow($where);
		
		return $result ? $result : null;
	}
	
	/**
	 * Получение всех возможных типов
	 *
	 * @return array
	 */
	public function getAll(){
		return $this->fetchAll();
	}
}