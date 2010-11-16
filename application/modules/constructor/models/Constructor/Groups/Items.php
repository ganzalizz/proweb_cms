<?php


class Constructor_Groups_Items extends Zend_Db_Table {

    protected $_name = 'site_constructor_groups_items';
    protected $_primary = array('id');	
    protected $_sequence = true;
    protected static $_instance = null;

     /**
     * Class to use for rows.
     *
     * @var string
     */
    protected $_rowClass = "Constructor_Groups_Items_Row" ;

    /**
     * Singleton instance
     *
     * @return Constructor_Groups_Items
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

   /**
     * Вставка массива ингредиентов для группы с id=$id
     * в массив групп
     * @param id группы ингредиентов
     * @return string Имя группы ингредиентов
     */
    public function insertItemsByGroupId(array $groups) {
        if (!count($groups)) return array();
        foreach ($groups as &$group) {
            $select = $this->select(false)
                ->from($this->_name, array('id', 'title'))
                ->where("id_group = ?", $group['id']);
            $group['items'] =  $this->getAdapter()->query($select)->fetchAll();
        }
        return count($groups)?$groups:array();
    }

    /**
     * Вставка массива ингредиентов в набор групп
     * @param id группы ингредиентов
     * @return string Имя группы ингредиентов
     */
    public function insertItemsToGroupRowset($type) {
        if (!$type && !count($types->_groups)) return null;
        $items = $this->fetchAll("id_type = '$type->id'");
        foreach ($items as $item){
            foreach ($type->_groups as $group) {
                if ($group->id == $item->id_group) {
                    $group->_values[] = $item;
                }
            }
        }
    }

    /**
     * Получение ингридиентов по массиву id
     * @ids массив id ингридиентов
     * @return 
     */
    public function getItemsByIdsArray($order) {

        if (!$order['items'] && !count($order['items'])) return $order;

        $where = '';

        foreach ($order['items'] as $id){
            $where .= $where?" OR $this->_name.id = '$id'":"$this->_name.id = '$id'";
        }
        $select = "SELECT $this->_name.id, $this->_name.title, site_constructor_prices.price, site_constructor_groups.title as 'group'
                    FROM $this->_name
                    LEFT JOIN site_constructor_prices
                    ON site_constructor_prices.id_type = ".$order['type_id'].
                    " AND site_constructor_prices.id_size = ".$order['size'].
                    " AND site_constructor_prices.id_item = $this->_name.id
                    LEFT JOIN site_constructor_groups
                    ON $this->_name.id_group = site_constructor_groups.id
                    WHERE $where";        
        $result = $this->getAdapter()->query($select)->fetchAll();

        $array = array();

        foreach ($result as $res) {
            $array[$res['group']][$res['id']]['title'] = $res['title'];
            $array[$res['group']][$res['id']]['price'] = $res['price'];
        }
        $order['items'] = $array;
        return $order;
    }
    
    /**
     * получение суммы ингредиентов 
     * @param array $order
     * @return int
     */
    public function getTotalPriceByItems($order){
    	$select = $this->select()
    		->setIntegrityCheck(false)
    		->from($this->_name, 'SUM(p.price) AS total')
    		->joinInner(
    			array('p'=>'site_constructor_prices'),
    			"p.id_type='{$order['type_id']}' AND 
    			 p.id_size = '{$order['size']}' AND 
    			 p.id_item = $this->_name.id",
    			array()    			
    		)
    		->where("$this->_name.id IN(".implode(',', $order['items']).")");    		
    	return $this->getAdapter()->fetchOne($select);	
    }

}