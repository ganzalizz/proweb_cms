<?php
/**
 * Articles main class
 * @author Vitaly
 * @version 1.0
 * @copyright www.proweb.by
 * @package roweb
 *
 */
class Articles extends Zend_Db_Table {

    /**
     * Константы для светофора.
     *
     * @var const
     */
    
     const TRAFFIC_LIGHTS_NONE = 0;
     const TRAFFIC_LIGHTS_RED = 1;
     const TRAFFIC_LIGHTS_YELLOW = 2;
     const TRAFFIC_LIGHTS_GREEN = 3;
     
     public static $traffic_light = array(
         self::TRAFFIC_LIGHTS_NONE => 'Нет',
         self::TRAFFIC_LIGHTS_RED => 'Красный',
         self::TRAFFIC_LIGHTS_YELLOW => 'Желтый',
         self::TRAFFIC_LIGHTS_GREEN => 'Зеленый'
     );
    

    /**
     * The default table name.
     *
     * @var string
     */
    protected $_name = 'site_articles';

    /**
     * The default primary key.
     *
     * @var array
     */
    protected $_primary = array('id');

    /**
     * Whether to use Autoincrement primary key.
     *
     * @var boolean
     */
    protected $_sequence = true; // Использование таблицы с автоинкрементным ключом

    /**
     * Singleton instance.
     *
     * @var St_Model_Layout_Pages
     */
    protected static $_instance = null;

   


    /**
     * Singleton instance
     *
     * @return Articles
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    

    /*
	*Возвращает все опбликованные статьи
	*
    */
    public function getAllPub($page_id, $ofset = null, $count = null) {
        $where=array();
        if (is_array($page_id)) {
            $where[] =$this->getAdapter()->quoteInto('pub= ?',1);
//            foreach ($page_id as $id) {
//                $where[] = $this->getAdapter()->quoteInto('page_id= ?',$id);
//            }
        } else {
            $where =array(
                    $this->getAdapter()->quoteInto('is_active= ?',1),
                    $this->getAdapter()->quoteInto('id_page= ?',(int)$page_id)
            );
            //$where =array( $this->getAdapter()->quoteInto('is_active= ?',1));
        }

        $order =array( $this->getAdapter()->quoteInto('created_at DESC',null),
                $this->getAdapter()->quoteInto('name ASC',null)
        );
        return $this->fetchAll($where,$order,$count,$ofset);
    }
    
     /**
     * получение списка всех новостей 
     * используется для админки
     * @param int $onpage
     * @param int $page
     * @return Zend_Paginator
     */
 	public function getAll($onpage, $page){
 		$select = $this->select()
 			->from($this->_name, array('*', 'date'=>'DATE_FORMAT(date, \'%d.%m.%Y\')'))
 			->order('date DESC');
 		return $this->getPaginator($select, $onpage, $page);	
 	}  


    
    
 	/**
     * @name addArticles Добавить 
     * @param Zend_Db_Table_Row $row
     * @param  array $data
     * 
     * @return Zend_Db_Table_Row inserted news
     */
    public function addArticles($row, $data) 
    {
       
        $data['created_at'] = (!isset($data['created_at'])) ? new Zend_Db_Expr('NOW()'): date('Y-m-d', strtotime($data['created_at']));
        $data['date'] = (!isset($data['date'])) ? new Zend_Db_Expr('NOW()') : date('Y-m-d', strtotime($data['date']));
        unset($data['id']); 
        $row->setFromArray($data);
        $row->save();
        return $row; 
       
    }
    
	/**
     * редактирование 
     * @param Zend_Db_Table_Row $row
     * @param array $data
     * @return Zend_Db_Table_Row
     */
    public function editArticles($row, $data) 
    {   
        unset($data['id']);
        if ($data['date']!=''){
        	$data['date'] = date('Y-m-d', strtotime($data['date']));
        }
        if ($data['created_at']!=''){
        	$data['created_at'] = date('Y-m-d', strtotime($data['created_at']));
        }        
        $row->setFromArray($data)->save();
       
        return $row;
        
    }
    

    /*
	*получение статью по $id
	*@param $id int
	*
    */
    public function getArticleById($id) {
        $where = $this->getAdapter()->quoteInto('id= ?',$id);
        return $this->fetchRow($where);
    }
    
	public function getItemByUrl($url){
    	$select = $this->select()
    		->from($this->_name, array('*', 'date'=>'DATE_FORMAT(date, \'%d.%m.%Y\')'))
    		->where('is_active = ?', 1)
    		->where('url = ?', $url);
    	return $this->fetchRow($select);	
    }


   
    
    /*
     *Выборка статьи по цвету светофора 
     * 
     * @param color int
     * @return mixed or false elswhere
     */
     public function getTrafficLightingByColor(){
         $ids = array(self::TRAFFIC_LIGHTS_RED,self::TRAFFIC_LIGHTS_YELLOW,self::TRAFFIC_LIGHTS_GREEN);
         $select = $this->select();
         $select->from($this->_name)
                ->where('is_active = ?', 1)
                ->where('lighting IN (?)', $ids);
             
         return $this->fetchAll($select);        

            
     }
     /**
      * 
      * @param int $item_per_page
      * @param int $page
      * @param string $year
      * @return Zend_Paginator
      */
     public function getActiveArticles($item_per_page, $page, $year = 'all')
     {
       
       $select = $this->select()
            ->from($this->_name, array('*', 'date'=>'DATE_FORMAT(date, \'%d.%m.%Y\')'))
            ->where('is_active = ?', true)
            ->order('date DESC');
       if ($year != 'all'){
       	 $select->where(new Zend_Db_Expr('YEAR(created_at) = ?'), $year);
       } 
        
       return $this->getPaginator($select, $item_per_page, $page);
                 
     }
     
     public function getYearForFilter()
     {
         $select = $this->select();
         $select->distinct()
                ->columns(new Zend_Db_Expr('YEAR(created_at) as years'))
                ->from($this->_name)
                ->group('years')
                ->order('years DESC');
        return $this->getAdapter()->fetchCol($select);
     }
     
     public function addCountViews($id)
    {
                  
        $where = $this->getAdapter()->quoteInto('id = ?', $id);
        $data['count_views'] = new Zend_Db_Expr('count_views+1');
        $this->update($data, $where);
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