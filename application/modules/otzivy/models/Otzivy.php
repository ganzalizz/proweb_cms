<?php
/**
 * Otzyvy main class
 * @author Vitaly
 * @version 1.0
 * @copyright www.proweb.by
 * @package roweb
 *
 */
class Otzivy extends Zend_Db_Table {

    
     const OTZIV_OTZIV = 0;
     const OTZIV_PREDLOZENIE = 1 ;     
     
     public static $otziv_vid = array(
         self::OTZIV_OTZIV => 'Отзыв',
         self::OTZIV_PREDLOZENIE => 'Предложение'
         
     );
         
    protected $_name = 'site_otzivy';   
    protected $_primary = array('id');    
    protected $_sequence = true;  
    protected static $_instance = null;

    

    /**
     * Singleton instance
     *
     * @return Otzivy
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    /**
     * 
     * @param int $page
     * @param int $onPage
     * @param int prizn
     * @return Zend_Paginator
     * 
     */
    public function getAll($page, $onPage, $prizn=null){
    	$select = $this->select()
    		->from($this->_name, array(
    			'*', 
    			'date'=>'DATE_FORMAT(added, \'%d.%m.%Y\')'
    		))    		
    		->order('added DESC');
    	if (!is_null($prizn)){
    		$select->where('prizn = ?', (int)$prizn);
    	}    	
    	return $this->getPaginator($select, $onPage, $page);	
    }

   
    /*
     *Возвращает все опбликованные отзывы или предложения
     *
     * @param $page_id
     * @param $offset
     * @param $count
     * @param $prizn
     * @return mixed or false
     */
    public function getAllPub($page_id, $prizn=self::OTZIV_OTZIV, $offset = null, $count = null) {
        $where=array();
        if (is_array($page_id)) {
            $where = array(
                     $this->getAdapter()->quoteInto('pub= ?',1),
                     $this->getAdapter()->quoteInto('prizn = ?', $prizn));

        } else {
            $where = array(
                     $this->getAdapter()->quoteInto('pub= ?',1),
                     $this->getAdapter()->quoteInto('id_page= ?',(int)$page_id),
                     $this->getAdapter()->quoteInto('prizn = ?', $prizn));
        }
        $order =array( $this->getAdapter()->quoteInto('created_at DESC',null));
        echo $prizn;
        $rez = $this->fetchAll($where,$order,$count,$offset);
        
        if (!count($rez)) return false;
        else return $rez; 
    }


    /**
     * Добавить отзыв предложение
     * @param  $data
     * @return $id
     */
    public function addOtziv($data) {
        if (!isset($data['added'])) {
            $data['added'] =new Zend_Db_Expr('NOW()'); //date("d-m-Y H:i:s");
        }        
        $data['is_active'] = 0;       
        $id=$this->createRow($data)->save();
        return $id;
    }

   

    /**
     * получение активных отзывов | предложений 
     * @param int $prizn
     * @param int $page
     * @param int $item_per_page
     * @return Zend_Paginator
     */
    public function getActiveOtzivy($prizn = null, $page = 1, $item_per_page = 20) {
    	$select = $this->select();
        $select ->where('is_active= ?',1)
        		->from($this->_name, array('*', 'date'=>'DATE_FORMAT(added, \'%d.%m.%Y\') '))
                ->order('added DESC');
                
        if (!is_null($prizn)){
        	$select->where('prizn = ?', (int)$prizn);
        }               
    	$adapter = new Zend_Paginator_Adapter_DbTableSelect($select);

                $paginator = new Zend_Paginator($adapter);
                $paginator->setCurrentPageNumber($page);
         return $paginator->setItemCountPerPage($item_per_page);
        
    }

	/**
     * 
     * @param Zend_Db_Table_Select $select
     * @param int $item_per_page
     * @param int $page
     * @return Zend_Paginator
     */
    private function getPaginator($select, $item_per_page, $page){
    	$adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        return $paginator->setItemCountPerPage($item_per_page);
        
    }

}