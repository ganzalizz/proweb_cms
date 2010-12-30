<?php

class PagesOptions extends Zend_Db_Table {
	
	protected $_name = 'site_pages_options';
	protected $_primary = array('id');	
	protected static $_instance = null;
	
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
	 * @return PagesOptions
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	/**
	 * Добавление новых html - данных
	 * при создании страницы
	 * 
	 * @param array $data
	 */
	public function addOptionsPage($data){
		$data = $this->getOptionsPage($data);
		$this->insert($data);
	}
	
	public function addOptions($data){
		$this->insert($data);
	}
	public function getOptions($item_id, $type){
		$where = array(
			$this->getAdapter()->quoteInto("item_id = ?", $item_id),
			$this->getAdapter()->quoteInto("type = ?", $type)
		);
		$res =  $this->fetchRow($where);
		if (is_null($res)){
			$res = $this->createRow(array('item_id'=>$item_id, 'type'=>$type, 'pageId'=>0));
			$res->save();
		}
		return $res;
	}
	
	/**
	 * Изменение данных при редактированиии страницы
	 *
	 * @param array $data
	 */
	public function editOptionsPage($data){
		$data = $this->getOptionsPage($data);
		
		$where = $this->getAdapter()->quoteInto("pageId = ?", $data['pageId']);
		$this->delete($where);
		$this->insert($data);
		
		
	}
	
	/**
	 * Удаление html-данных страницы
	 *
	 * @param string $where
	 */
	public function deleteOptions($where){
		$this->delete($where);
	}
	
	/**
	 * Получение html-свойств страницы
	 *
	 * @param unknown_type $pageId
	 * @param unknown_type $order
	 * @return unknown
	 */
	public function getPageOptions($pageId, $order = null){
		$where = $this->getAdapter()->quoteInto('pageId = ?', (int)$pageId);
		$options = $this->fetchRow($where, $order);
		if (is_null($options)){
			$options = $this->createRow();
		}
		
		return $options;
	}
	
	/**
	 * Получение данных новой копии страницы
	 *
	 * @param int $pageId
	 * @param int $newPageId
	 */
	public function addOptionsCopyPage($pageId, $newPageId){
		$where = $this->getAdapter()->quoteInto('pageId = ?', $pageId);
		$options = $this->fetchRow($where);
		$options = $options->toArray();
		unset($options['id']);
		$options['pageId'] = $newPageId;
		//print_r($options); exit;
		$this->insert($options);
	}
	
	/**
	 * Добавление новой языковой версии
	 *
	 * @param int $oldId
	 * @param int $newId
	 */
	public function addVersion($oldId, $newId){
		$options = PagesOptions::getInstance()->getPageOptions($oldId);
		
		if($options){
			$options = $options->toArray();
			$options['pageId'] = (int)$newId;
			unset($options['id']);
			$this->insert($options);
		}
	}
	

	/**
	 * Вбивание данных при добавлении новой страницы
	 *
	 * @param array
	 * @return array
	 */
	private function getOptionsPage($data){
		return array(
			'pageId' => isset($data['id']) ? $data['id'] : '',
			'keywords' => isset($data['keywords']) ? $data['keywords'] : '',
			'h1' => isset($data['h1']) ? $data['h1'] : '',
			'descriptions' => isset($data['descriptions']) ? $data['descriptions'] : '',
			'title' => isset($data['page_title']) ? $data['page_title'] : ''
			
			);
	}

}