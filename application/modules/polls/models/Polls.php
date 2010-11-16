<?php

class Polls extends Zend_Db_Table {

    protected $_name = 'site_poll';
    protected $_primary = array('id');
    protected static $_instance = null;

    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function getPollNameBiId($poll_id){
        if (!$poll_id) return '';
        $rowset   = $this->fetchRow("id=$poll_id");
        $rowset = $rowset->toArray();
        return $rowset['title'];
    }

    public function getActivePolls($active=NULL, $onpage=NULL, $offset=NULL) {
        $where = $active?"WHERE active = 1 RAND()":NULL;
        $sql = "SELECT * FROM $this->_name $where ORDER BY priority DESC LIMIT ".(int)$offset.", ".(int)$onpage;
        $poll = $this->getAdapter()->fetchAll($sql);
        return $poll;
    }

    public function getPoll($id=0) {
        $where = "active = 1";
        if ($id) $where .=" AND id = ".$id;
        $poll = $this->fetchAll($where, 'RAND()', 1, NULL);
        return $poll;
    }
}