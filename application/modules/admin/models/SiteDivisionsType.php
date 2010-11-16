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

	public function getDivisions($module)
        {
            $rows = $this->fetchAll("`module` = '$module'",'priority DESC');
            return $rows;
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


}