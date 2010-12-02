<?php

class Trash extends Zend_Db_Table {
	
	protected $_name = 'site_content';
	protected $_primary = array('id');
	protected static $_instance = null;
	
	/**
	 * Singleton instance
	 *
	 * @return Trash
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	/**
	 * Добавление страницы в корзину
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function add($id){
		$where = $this->getAdapter()->quoteInto("id = ?", $id);
		$this->update(
			array('deleted' => '1', 
				'deletedBy' => Security::getInstance()->getUser()->id, 
				'published' => '0',
				'unpubDate' => date("Y-m-d H:i:s")), 
			$where);
	
		return true;	
	}
	
	/**
	 * Очистка корзины
	 *
	 * @return boolean
	 */
	public function clear() {
		Loader::loadCommon('Router');
		$where = $this->getAdapter()->quoteInto("deleted = ?", 1);
		$all = $this->fetchAll($where);
		
		foreach ($all as $data){
			//$ids[] = $data->id;
			Router::getInstance()->deleteRoute($data->path);			
			Pages::getInstance()->remove($data->id);
		}
		return true;	
	}
	
	/**
	 * Восстановление страницы из корзины
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function restore($id){
		$where = $this->getAdapter()->quoteInto("id = ?", $id);
		$this->update(
			array('deleted' => '0', 
				'published' => '1'),
			$where);
	
		return true;	
	}
	
	/**
	 * Получение всех удаленных 
	 * в корзину страниц
	 *
	 * @param string $order
	 * @return array
	 */
	public function getPages($order = null){
		$where = $this->getAdapter()->quoteInto('deleted = ?', 1);
		$pages = $this->fetchAll($where, $order);

		return $pages;
	}
}