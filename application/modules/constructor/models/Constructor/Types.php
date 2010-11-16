<?php


class Constructor_Types extends Zend_Db_Table {

    protected $_name = 'site_constructor_type';
    protected $_primary = array('id');	
    protected $_sequence = true;
    protected static $_instance = null;

    /**
     * Class to use for rows.
     *
     * @var string
     */
    protected $_rowClass = "Constructor_Types_Row" ;

    /**
     * Singleton instance
     *
     * @return ConstructorTypes
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Получение имени типа блюда по id
     * @param id типа блюда
     * @return string Имя типа блюда
     */
    public function getNameById($id) {
        if (!$id) return '';
        $select = $this->select(false)
            ->from($this->_name, 'title')
            ->where("id = ?", $id)
            ->limit(1);
        $result =  $this->getAdapter()->query($select)->fetch();
        return count($result)?$result['title']:'';
    }

    /**
     * Получение всех активных типов блюд
     * @return array
     */
    public function getAllActiveTypesForSelect() {
        $result = array();
        $select = $this->select(false)
                        ->from($this->_name, array('id', 'title'))
                        ->where("active = ?", '1')
                        ->order('priority DESC');
        $types =  $this->getAdapter()->query($select)->fetchAll();
        if (count($types)) {
            foreach ($types as $type) {
                $result[$type['id']] = $type['title'];
            }
        }
        return count($result)?$result:array();
    }

    /**
     * Получение первого активного типа блюд
     * @return object
     */
    public function getFirstActiveType() {
        $select = $this->select(false);
        $type = $this->fetchRow($select->where("active = ?", '1'), $select->order('priority DESC'));
        return $type?$type:NULL;
    }

    /**
     * Получение активного типа блюд по id
     * @return array
     */
    public function getActiveTypeById($type_id) {
        $type_id = (int)$type_id;
        $select = $this->select(false);
        $type = $this->fetchRow($select->where("active = ?", '1')->where("id = ?", $type_id));
        return $type?$type:NULL;
    }


}