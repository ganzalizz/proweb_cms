<?php


class Constructor_Sizes extends Zend_Db_Table {

    protected $_name = 'site_constructor_sizes';
    protected $_primary = array('id');	
    protected $_sequence = true;
    protected static $_instance = null;

    /**
     * Singleton instance
     *
     * @return Constructor_Sizes
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Вставка массива размеров для блюда с id_type=$id
     * в массив ингредиентов групп этого блюда
     * @param id группы ингредиентов
     * @return array
     */
    public function insertSizesByTypeId($groups, $type_id) {
        if (!count($groups)) return array();
        $select = $this->select(false)
                    ->from($this->_name, array('id', 'title'))
                    ->where("id_type = ?", $type_id);
        $sizes =  $this->getAdapter()->query($select)->fetchAll();

        foreach ($groups as &$group) {
            foreach ($group['items'] as &$item) {
                $item['sizes'] = $sizes;
            }
        }
        return count($groups)?$groups:array();
    }

    /**
     * Получение имени размера блюда по id
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
     * Получение цены размера блюда по id
     * @param id типа блюда
     * @return string Имя типа блюда
     */
    public function getSizePrice($id) {
        if (!$id) return '';
        $select = $this->select(false)
            ->from($this->_name, 'price')
            ->where("id = ?", $id)
            ->limit(1);
            
        return (int)$this->getAdapter()->fetchOne($select);
    }

    /**
     * Получение размеров для блюда с id_type=$id
     * @param id группы ингредиентов
     * @return array
     */
    public function getSizesByTypeId($type_id) {
        if (!(int)$type_id) return array();
        $select = $this->select(false)
                    ->from($this->_name, array('id', 'title'))
                    ->where("id_type = ?", $type_id);
        $sizes =  $this->getAdapter()->query($select)->fetchAll();
        return count($sizes)?$sizes:array();
    }

    /**
     * Вставка размеров для блюда с id_type=$id
     * @param id группы ингредиентов
     * @return object Types
     */
    public function insertSizesToTypeRow($type) {
        if (!$type) return null;
        $select = $this->select(false);        
        $type->_sizes =  $this->fetchAll($select->where("id_type = ?", $type->id));
    }

    /**
     * Вставка массива размеров в набор групп
     * @param id группы ингредиентов
     * @return string Имя группы ингредиентов
     */
    public function insertSizesToGroupRowset(&$groups, $type_id) {
        $type_id = (int)$type_id;
        if (!count($groups) || !(int)$type_id) return NULL;
        $items = $this->fetchAll("id_type = '$type_id'");
        foreach ($items as $item){
            foreach ($groups as $group) {
                if ($type_id == $item->id_type) {
                    $group->_sizes = $item;
                }
            }
        }
        return count($groups)?$groups:array();
    }
}