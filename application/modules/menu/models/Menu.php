<?php

//require_once ('Zend/Db/Table.php');

class Menu extends Zend_Db_Table {
	protected $_name = 'site_menu';
	
	protected static $_instance = null;
	
	/**
	 * Class to use for rows.
	 *
	 * @var string
	 */
	protected $_rowClass = "Menu_Row";
	
	/**
	 * Class to use for row sets.
	 *
	 * @var string
	 */
	protected $_rowsetClass = "Menu_Rowset";
	
	
	/**
	 * Dependent tables.
	 *
	 * @var array
	 */
	protected $_dependentTables = array(
		
	) ;

	/**
	 * Reference map.
	 *
	 * @var array
	 */
	protected $_referenceMap = array(
		'Pages' => array(
            'columns'           => array('pageId'),
            'refTableClass'     => 'Pages',
            'refColumns'        => array('id'),
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::RESTRICT
        )
	) ;
	
	
	/**
	 * Singleton instance
	 *
	 * @return Menu
	 */
	public static function getInstance() {
		if (null === self::$_instance) {
			self::$_instance = new self ( );
		}
		
		return self::$_instance;
	}
	
	/**
	 * Получение меню 
	 *
	 * @param string $type
	 * @param int $depth глубина меню
	 * @return Zend_Db_Table_Rowset|null
	 */
	public function getMenu($type, $depth = 1) {
		
		$type = $this->getType ( $type );
		if (!is_null($type)){		
		
			$select = $this->select()
				->setIntegrityCheck(false)
				->from(array('sm'=>$this->_name), array())
				->joinInner(
					array('sc'=>'site_content'),
					'sc.id=sm.pageId',
					array('sc.name', 'sc.path', 'sc.level', 'sc.sortId', 'sc.parentId', 'sc.id')
				)
				->where('sm.typeId = ?', $type->id)
				->where('sc.published = ?', 1)
				->where('sc.deleted = ?', 0)
				->where('sc.level <= ?', $depth)
				->order(array('sc.level', 'sc.sortId'));
				
			return $this->fetchAll($select);
		
		}
		
		return null;
	}
	
	/**
	 * Удаление страницы из меню
	 *
	 * @param string $where
	 * @return boolean
	 */
	public function deleteMenu($where) {
		$this->delete ( $where );
		
		return true;
	}
	
	/**
	 * Получение страницы, зарегеной в меню
	 *
	 * @param int $pageId
	 * @return object
	 */
	public function getMenuPage($pageId) {
		$result = array ( );
		$where = $this->getAdapter ()->quoteInto ( 'pageId = ?', $pageId );
		
		$result = $this->fetchAll ( $where );
		$return = array ( );
		
		foreach ( $result as $key => $data ) {
			$return [$data->typeId] = $data;
		}
		
		return $return;
	}
	
	/**
	 * Добавление страницы в меню
	 *
	 * @param int $id
	 * @param array $data
	 * @return boolean
	 */
	public function addMenu($id, $data) {
		foreach ( $data as $id_type ) {
			if ($id_type){
				$this->insert ( array ('pageId' => $id, 'typeId' => $id_type ) );
			}
		}		
		return true;
	}
	
	/**
	 * Обновление даннах страницы
	 * в меню
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function editMenu($data) {
		$id = $data ['id'];
		$where = $this->getAdapter ()->quoteInto ( "pageId = ?", $id );
		$this->delete ( $where );
		
		if (isset ( $data ['menu'] )) {
				
			$this->addMenu ( $id, $data ['menu'] );
		}
		
		return true;
	}
	
	/**
	 * Добавление новой языковой версии
	 *
	 * @param int $oldId
	 * @param int $newId
	 */
	public function addVersion($oldId, $newId) {
		$menu = Menu::getInstance ()->getMenuPage ( $oldId );
		
		if (empty ( $menu )) {
			return;
		}
		
		$types = array ( );
		
		foreach ( $menu as $k => $d ) {
			$types [] = $k;
		}
		
		$this->addMenu ( $newId, $types );
	
	}
	
	/**
	 * Получение ID переданных страниц
	 *
	 * @param array $array
	 * @return array
	 */
	private function getPageIds($array) {
		$array = $array ? $array : array ( );
		$pageIds = array ( );
		
		foreach ( $array as $data ) {
			$pageIds [] = $data->pageId;
		}
		
		return $pageIds;
	}
	
	/**
	 * Генерация пути с разделителем '/'
	 *
	 * @param array $result
	 */
	private function appendPath($result) {
		$pageIds = $this->getPageIds ( $result );
		$pages = $this->getPages ( $pageIds );
		
		foreach ( $result as $data ) {
			$result ['path'] = '/';
		}
	}
	
	/**
	 * Получение страниц с переданными ID
	 *
	 * @param array $PageIds
	 * @return array
	 */
	private function getPages($PageIds) {
		Loader::loadPublicModel ( 'Pages' );
		$pages = Pages::getInstance ()->getPages ( $pageIds );
		
		return $pages;
	}
	
	/**
	 * Получение типа меню(гориз., верт...)
	 *
	 * @param string $type
	 * @return string
	 */
	private function getType($type) {
		//Loader::loadPublicModel ( 'MenuTypes' );
		$result = MenuTypes::getInstance ()->getType ( $type );
		
		return $result;
	}
}