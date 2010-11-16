<?php


class Constructor_Prices extends Zend_Db_Table {

    protected $_name = 'site_constructor_prices';
    protected $_primary = array('id');	
    protected $_sequence = true;
    protected static $_instance = null;

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
     * Вставка массива цен для блюда с id_type=$id
     * в массив ингредиентов групп этого блюда
     * @param id группы ингредиентов
     * @return array
     */
    public function insertPricesByTypeId($groups, $type_id) {
        if (!count($groups)) return array();

        foreach ($groups as &$group) {
            foreach ($group['items'] as &$item) {
                foreach ($item['sizes'] as &$size) {
                    $select = $this->select(false)
                                    ->from($this->_name, 'price')
                                    ->where("id_type = ?", $type_id)
                                    ->where("id_item = ?", $item['id'])
                                    ->where("id_size = ?", $size['id']);
                    $price = $this->getAdapter()->query($select)->fetch();
                    $size['price'] = $price['price'];
                }
            }
        }
        return count($groups)?$groups:array();
    }

    /**
     * Вставка массива цен для блюда с id_type=$id
     * в массив ингредиентов групп этого блюда
     * @param id группы ингредиентов
     * @return array
     */
    public function getRowId($type_id, $item_id, $size_id) {
        $type_id = (int)$type_id;
        $item_id = (int)$item_id;
        $size_id = (int)$size_id;
        $select = $this->select(false)
                        ->from($this->_name, 'id')
                        ->where("id_type = ?", $type_id)
                        ->where("id_item = ?", $item_id)
                        ->where("id_size = ?", $size_id);
        $id = $this->getAdapter()->query($select)->fetch();
        return count($id)?$id['id']:0;
    }

    /**
     * Вставка набора строк цен в набор ингридиентов
     * @param type Тип блюда для вставки
     * @return object Types
     */
    public function insertPricesToItemsRowset($type) {        
        if (!$type) return null;
        $prices = $this->fetchAll("id_type = '$type->id'");
        foreach ($type->_sizes as $size){
            foreach ($prices as $price){
                foreach ($type->_groups as $group) {
                    foreach ($group->_values as $item){
                        if ($item->id == $price->id_item && $price->id_size == $size->id) {
                            $item->_prices[$size->id] = $price;
                        }
                    }
                }
            }
        }
    }


    /**
     * Получение цены для ингридиента
     * в массив ингредиентов групп этого блюда
     * @param id группы ингредиентов
     * @return int
     */
    public function getPrice($type_id, $item_id, $size_id) {
        $type_id = (int)$type_id;
        $item_id = (int)$item_id;
        $size_id = (int)$size_id;
        $select = $this->select(false)
                        ->from($this->_name, 'price')
                        ->where("id_type = ?", $type_id)
                        ->where("id_item = ?", $item_id)
                        ->where("id_size = ?", $size_id)
                        ->limit(1);
        $price = $this->getAdapter()->query($select)->fetch();
        return $price['price']?$price['price']:0;
    }
}