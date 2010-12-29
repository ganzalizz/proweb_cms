<?php

/*

CREATE TABLE IF NOT EXISTS `site_divisions_type` (
  `id` int(11) NOT NULL auto_increment,
  `system_name` varchar(100) NOT NULL,
  `title` varchar(128) NOT NULL,
  `module` varchar(128) NOT NULL,
  `controller_frontend` varchar(128) NOT NULL,
  `action_frontend` varchar(128) NOT NULL,
  `controller_backend` varchar(128) NOT NULL,
  `action_backend` varchar(128) NOT NULL,
  `priority` int(5) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

*/


class SiteDivisionsType extends Zend_Db_Table {

	protected $_name = 'site_divisions_type';
	protected $_primary = array('id');
	protected static $_instance = null;

	/**
	 * Singleton instance
	 *
	 * @return SiteDivisionsType
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	/**
	 * 
	 * получение типов раздела к модулю
	 * @param string $module
	 * @return Zend_Db_Table_Rowset
	 */
	public function getDivisions($module)
        {
        	$select = $this->select()
        		->where('module = ?', $module)
        		->order('priority DESC');
            return $this->fetchAll($select);
            
        }
	/**
	 * Поднять приоритет
	 *
	 * @param int $id
	 *
	 */
    public function itemUp($id)
	{
		$rows = $this->fetchAll($this->select()->from($this->_name, array('id', 'priority'))
												->order('priority ASC'));

		$rows = $rows->toArray();

		$sz = sizeof($rows);
		if ($sz > 0) {
			for($i=0;$i<$sz;$i++) {
				if ($rows[$i]['id'] == $id) {
					//Если есть куда поднимать
					if ($i>0) {
						$new_priority=$rows[$i-1]['priority']-1;
						$data = array( 'priority' => $new_priority );
						$this -> update($data,$id);
						break;
					} else {
						break;
					}
				}
			}
		}
	}

	/**
	 * Понизить приоритет
	 *
	 * @param int $id
	 *
	 */
    public function itemDown($id, $parent)
	{
		$rows = $this->fetchAll($this->select()->from($this->_name, array('id', 'priority'))
												->order('priority ASC'));

		$rows = $rows->toArray();

		$sz = sizeof($rows);
		if ($sz > 0) {
			for($i=0;$i<$sz;$i++) {
				if ($rows[$i]['id'] == $id) {
					//Если есть куда поднимать
					if ($i < $sz-1) {
						$new_priority=$rows[$i+1]['priority']+1;
						$data = array( 'priority' => $new_priority );
						$this -> editItem($data,$id);
						break;
					} else {
						break;
					}
				}
			}
		}

	}


	public function updatePages($id_type, $value=0){
		$pages = Pages::getInstance();
		$where = $pages->getAdapter()->quoteInto('id_div_type = ?', $id_type);
		$pages->update(array('inside_items'=>(int)$value), $where);
	}
	/**
	 *
	 * @param string $system_name
	 * @return string
	 */
	public function getPagePathBySystemName($system_name){
		$sql = "SELECT
				  site_content.path
				FROM $this->_name
				  INNER JOIN site_content
				    ON ($this->_name.id = site_content.id_div_type)
				WHERE $this->_name.system_name = '$system_name'
				LIMIT 1";
		return $this->getAdapter()->fetchOne($sql);

	}
	/**
	 * 
	 * Получение списка Активных разделов
	 * @return array
	 */
	public function getAllActive(){
		$return  = array();
		$select = $this->select()
			->where('active = ?', 1)
			->order('priority desc');
		$rowSet = $this->fetchAll($select);
		if ($rowSet->count()){
			foreach ($rowSet as $item){
				$return[$item->id] = $item->title;
			}
		}
		return $return;	
			
	}
	/**
	 * 
	 * получение раздела по id
	 * @param int $id
	 * @return array
	 */
	public function getOne($id){
		$row = $this->find($id)->current();
		return array($row->id=>$row->title);
	}


}