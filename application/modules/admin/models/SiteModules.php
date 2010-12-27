<?php

/*

CREATE TABLE IF NOT EXISTS `site_modules` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `instdata` datetime NOT NULL,
  `last_edit` TIMESTAMP,
  `priority` int(5) NOT NULL,
  `active` tinyint(2) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

*/


class SiteModules extends Zend_Db_Table {
	
	protected $_name = 'site_modules';
	protected $_primary = array('id');
	protected static $_instance = null;
	
	/**
	 * Получить объект класса
	 *
	 * @return SiteModules
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	
	/**
	 * Изменить состояние элемента
	 *
	 * @param int $id
	 *	
	 */
	public function activeItem($id) {
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		
		$res = $this->fetchRow($where);
		
		$active = ($res->active == '1') ? '0' : '1';
		
		$this->update ( array ('active' => $active ), $where );
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
      
      

}