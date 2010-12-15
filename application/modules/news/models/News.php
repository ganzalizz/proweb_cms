<?php
/**
 * News main class
 * @author Vitaly
 * @version 1.0
 * @copyright www.proweb.by
 * @package proweb
 *
 */
class News extends Zend_Db_Table {


    /**
     * The default table name.
     *
     * @var string
     */
    protected $_name = 'site_news';

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
     * тут хранятся значения главных тэгов для таблицы
     * @var array
     */
    private $_main_tags = null;


    /**
     * Singleton instance
     *
     * @return News
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    
    
    
    

   
    
    /**
     * @name getAllActiveExt Получить все is_active = true новости
     *       
     *                       
     *
     * @param $is_main = false
     * @param $is_hot = false
     * $is_main=false $is_hot=false только новости is_active
     * $is_main=true $is_hot=false только новости is_active and is_main
     * $is_main=false $is_hot=true только новости is_active and is_hot
     * $is_main=true $is_hot=true только новости is_active and is_hot and is_true
     *
     * @return mixed|false 
     *
     * @see
     */
    public function getAllActiveExt($is_main = false, $is_hot= false) 
    {
        
        $select = $this->select()
                 ->from('site_news')
                 ->where('is_active = ?', true)
                 ->where('is_main = ?', $is_main)
                 ->where('is_hot = ?', $is_hot)
                 ->order('created_at DESC');
      return $result = $this->fetchAll($select);
    }
    
        
    /**
     * @name getIsMain Получить все is_main новости
     *
     * @param 
     *
     * @return mixed|false 
     *
     * @see
     */
     public function getIsMain()
     {
         $select = $this->select()
                 ->from('site_news')
                 ->where('is_active = ?', true)
                 ->where('is_main = ?', true)
                 ->order('created_at DESC');
        return $result = $this->fetchAll($select);
     }
     
    /**
     * @name getIsHot Получить все is_hot новости
     *
     * @param 
     *
     * @return mixed|false 
     *
     * @see
     */
      public function getIsHot()
     {
         $select = $this->select()
                 ->from('site_news')
                 ->where('is_hot = ?', true)
                 ->where('is_main = ?', true)
                 ->order('created_at DESC');
        return $result = $this->fetchAll($select);
     }  
     
    

    /**
     * @name addNews Добавить новость
     * @param Zend_Db_Table_Row $row
     * @param  array $data
     * 
     * @return Zend_Db_Table_Row inserted news
     */
    public function addNews($row, $data) 
    {
       
        $data['created_at'] = (!isset($data['created_at'])) ? new Zend_Db_Expr('NOW()'): date('Y-m-d', strtotime($data['created_at']));
        $data['date_news'] = (!isset($data['date_news'])) ? new Zend_Db_Expr('NOW()') : date('Y-m-d', strtotime($data['date_news']));
        unset($data['id']); 
        $row->setFromArray($data);
        $row->save();
        return $row; 
       
    }

    /**
     * @name getNewById($id) получение новости по $id
     *
     *@param $id int
     * 
     *@return mixed|null
     *
    */
    public function getNewsById($id) 
    {
        $where = $this->getAdapter()->quoteInto('id= ?',$id);
        return $result = $this->fetchRow($where);
    }

    
    public function getItemByUrl($url){
    	$select = $this->select()
    		->from($this->_name, array('*', 'date'=>'DATE_FORMAT(date_news, \'%d.%m.%Y\')'))
    		->where('is_active = ?', 1)
    		->where('url = ?', $url);
    	return $this->fetchRow($select);	
    }

    
    
    /**
     * редактирование новости
     * @param Zend_Db_Table_Row $row
     * @param array $data
     * @return Zend_Db_Table_Row
     */
    public function editNews($row, $data) 
    {         
        
        unset($data['id']);
        if ($data['date_news']!=''){
        	$data['date_news'] = date('Y-m-d', strtotime($data['date_news']));
        }
        if ($data['created_at']!=''){
        	$data['created_at'] = date('Y-m-d', strtotime($data['created_at']));
        }        
        $row->setFromArray($data)->save();
       
        return $row;
        
    }
    
    public function addCountViews($id)
    {
        $where = $this->getAdapter()->quoteInto('id = ?', $id);
        $data['count_views'] = new Zend_Db_Expr('count_views+1');
        $this->update($data, $where);
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
 			->from($this->_name, array('*', 'date'=>'DATE_FORMAT(date_news, \'%d.%m.%Y\')'))
 			->order('date_news DESC');
 		return $this->getPaginator($select, $onpage, $page);	
 	}  
  

   

   

     /**
     * @name getNewsPaginator получить NewsPaginator
     * 
     * @param  $data mixed
     * 
     * @return Zend_Paginator
     */
    public function getNewsPaginator($item_per_page, $page)
    { 
        $adapter = new Zend_Paginator_Adapter_DbTableSelect(
                $this->select()
                     ->from($this->_name, array('*', 'date'=>'DATE_FORMAT(date_news, \'%d.%m.%Y\')'))
                     ->where('is_active = ?', true)
                     ->order('date_news DESC'));

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