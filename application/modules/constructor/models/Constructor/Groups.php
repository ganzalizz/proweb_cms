<?php


class Constructor_Groups extends Zend_Db_Table {

    protected $_name = 'site_constructor_groups';
    protected $_primary = array('id');
    protected $_sequence = true;
    protected static $_instance = null;

    /**
     * Class to use for rows.
     *
     * @var string
     */
    protected $_rowClass = "Constructor_Groups_Row" ;

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
     * Получение имени группы ингредиентов по id
     * @param id группы ингредиентов
     * @return string Имя группы ингредиентов
     */
    public function getNameById($id) {
        if (!$id) return '';
        $select = $this->select(false)
            ->from($this->_name, 'title')
            ->where("id = ?", $id);
        $result =  $this->getAdapter()->query($select)->fetchAll();
        return count($result)&&isset($result[0]['title'])?$result[0]['title']:'';
    }

    /**
     * Получение массива групп для блюда с id_type=$id
     * @param id группы ингредиентов
     * @return string Имя группы ингредиентов
     */
    public function getGroupsByTypeId($id) {
        if (!$id) return array();
        $select = $this->select(false)
            ->from($this->_name, array('id', 'title'))
            ->where("id_type = ?", $id);
        $result =  $this->getAdapter()->query($select)->fetchAll();
        return count($result)?$result:array();
    }

    /**
     * Вставка набора строк групп в строку блюда с id_type=$id
     * @param id типа блюда
     * @return object Types
     */
    public function insertGroupsRowsetTypeRow(&$type) {
        if (!$type) return null;
        $type->_groups =  $this->fetchAll("id_type=$type->id");
    }






}