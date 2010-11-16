<?php
class Pollsitems extends Zend_Db_Table {
	
	protected $_name = 'site_poll_items';
	protected $_primary = array('id');
	protected static $_instance = null;
	
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

        public function getItemsByParentId($id, $onpage=NULL, $offset=NULL){
            $where = $this->getAdapter ()->quoteInto ( 'id_parent = ?', $id );
            $items = $this->fetchAll($where, 'priority DESC', (int)$onpage, (int)$offset);
            return $items;
        }

        public function getActiveItemsByParentId($id, $onpage=NULL, $offset=NULL){
            $where = $this->getAdapter ()->quoteInto ( 'id_parent = ?', $id, 'active=?', 1 );
            $items = $this->fetchAll($where, 'priority DESC', (int)$onpage, (int)$offset);
            return $items;
        }

        public function getActiveItemsCount($poll_id){
            $rowset   = $this->fetchAll("id_parent=$poll_id AND active=1");
            return count($rowset);
        }

        public function getAciveItems(&$polls) {
            if (!count($polls)) return array();
            foreach ($polls as &$poll) {
                $where = "id_parent =". $poll['id'] ." AND active = 1";
                $items = $this->fetchAll($where, 'priority DESC');
                $items = $items->toArray();
                if (count($items))
                    $poll['childs'] = $items;
                else
                    $poll['childs'] = array();
            }
            return $polls;
        }

        public function getItemById($id){           
            if (!$id) return array();
            $item = $this->fetchRow("id=".(int)$id);
            return $item->toArray();
        }

        public function getSum($id){
            $sum = 0;
            if (!$id) return array();
            $items = $this->fetchAll("id_parent=".(int)$id);
            foreach ($items as $item) {
                $sum += $item->votecount;
            }
            return $sum;
        }

	
}