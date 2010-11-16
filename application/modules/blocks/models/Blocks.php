<?php


class Blocks extends Zend_Db_Table {

    protected $_name = 'site_blocks';
    protected $_primary = array('id');	
	protected $_sequence = true;
    protected static $_instance = null;
    /**
     * 
     * @var Zend_Cache_Frontend_Class
     */
    protected $_cache = null;

    /**
     * Singleton instance
     *
     * @return Blocks
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    /**
     * возвращает контент в виде массива разитого по разделителю. только для типа text
     * @param string $system_name
     * @param string $separator
     */
    public function getContentAsArray($system_name , $separator=';'){
    	$row  = $this->fetchRow("system_name='$system_name' AND type='text'");
    	if ($row!=null){
    		$array_content =  explode($separator, $row->content);
    		$res = array();
    		foreach ((array)$array_content as $key=>$value){
    			$res[$value]= $value;
    		}
    		return $res;
    	}
    	return array();
    }
    
    /**
     * получение контента блока по system_name
     * @param string $system_name
     * @param string $order
     */
    public function getContentBySystemName($system_name,  $order = null){
    	$select = $this->select();
    	$select->where("system_name = ?", $system_name);
    	$select->where("active = ?", 1);
    	if ($order){
    		$select->order( $order);
    	}
    	$row = $this->fetchRow($select);
    	if ($row!=null){
    		return $row->content;
    	}
    	return '';
    }
   

   
	
   

}