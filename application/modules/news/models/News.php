<?php
/**
 * News main class
 * @author Vitaly
 * @version 1.0
 * @copyright www.proweb.by
 * @package roweb
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
     * Dependent tables.
     *
     * @var array
     */
    protected $_dependentTables = array(

            ) ;


	
    

    /**
     * Class to use for rows.
     *
     * @var string
     */
    protected $_rowClass = "News_Row" ;

    /**
     * Class to use for row sets.
     *
     * @var string
     */
    protected $_rowsetClass = "News_Rowset" ;


    private $_Paths = null;

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
    
    
    
    
    public function getCount($where) {
        $sql = "SELECT	 COUNT(*) AS count FROM site_news WHERE $where";
        return $this->getAdapter()->fetchOne($sql);
    }

    /**
     * @name getAllActive Получить все is_active = true новости
     *
     * @param $is_main =
     *
     * @return mixed|false 
     *
     * @see
     */
    public function getAllActive() 
    {
       
        $select = $this->select();
        $select->where('is_active = ?', true)
               ->order('created_at DESC');
        return $result = $this->fetchAll($select);
    }
    
    public function getAllActivePage($item_per_page, $offset)
    {
        $select =   $this->select()
                         ->from($this->_name)
                         ->where('is_active = ?', true)
                         ->order('created_at DESC')
                         ->limit($item_per_page, $offset);
        return $result = $this->fetchAll($select);
                
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
     *@return mixed|false
     *
    */
    public function getNewsById($id) 
    {
        $where = $this->getAdapter()->quoteInto('id= ?',$id);
        return $result = $this->fetchRow($where);
    }


    public function setNewsIsActive($id) {
        
        $where = $this->getAdapter()->quoteInto('id = ?', $id);
        return $this->update(array('is_active' => 1), $where);
     }
    /*
    *заблокировать новость
    *@param $id int
    *
    */
    public function unpubNew($id) {
        $this->
        $array = array('pub'=>0);
        $new = $this->find($id)->current();
        $new->setFromArray(array_intersect_key($array, $new->toArray()));
        $new->save();
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



    /*
	*копирование новости
	*@param $id int
	*
    */
    public function CopyNew($id) {

        $new= $this->getNewById($id);
        $new = $new->toArray();
        $new['url'] = $new['url'].'_copy'.rand(1,20);
        $new['created_at'] = new Zend_Db_Expr('NOW()');
        if ($new['pub']==1) {
            $new['pub_date']=new Zend_Db_Expr('NOW()');
        }
        unset($new['id']);

        return $this->insert($new);
    }

    /**
     * Удалить новость
     * @param <type> $id
     */
    public function deleteNews($id) {
        $new = $this->find($id)->current();
        if($new!=null) $new->delete();
    }
    
    /*
    *сделать новость главной (для отображения на главной странице)
    *@param $id int
    *
    */
    public function setMainNew($id) {
        $where = $this->getAdapter()->quoteInto('id= ?',$id);
        $data=array('main'=>1);
        return $this->update($data,$where);
    }

    public function unsetMainNew($id) {
        $where = $this->getAdapter()->quoteInto('id= ?',$id);
        $data=array('main'=>0);
        return $this->update($data,$where);
    }

    /*
    *возвращает все новости устанавленные как главные и опубликованные
    * @param array news
    *
    */
    public function getMain($type='news', $limit=0) {
        $select = new Zend_Db_Select($this->getAdapter());
        $select ->where($this->getAdapter()->quoteInto('pub= ?',1))
                ->where($this->getAdapter()->quoteInto('main= ?',1))
               // ->where($this->getAdapter()->quoteInto('type= ?',$type))
                ->from($this->_name)
                ->order($this->getAdapter()->quoteInto('RAND()', null))
                ->limit($limit);
        return $this->getAdapter()->query($select);
    }

    /*
    * возвращает все новости устанавленные как опубликованные
    * @param array news
    *
    */
    public function getActiveNews($type='news', $limit=0) {
        $select = new Zend_Db_Select($this->getAdapter());
        $select ->where($this->getAdapter()->quoteInto('pub= ?',1))
               // ->where($this->getAdapter()->quoteInto('type= ?',$type))
                ->from($this->_name)
                ->order($this->getAdapter()->quoteInto('created_at DESC', null))
                ->limit($limit);
        return $this->fetchAll($select);
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
                     ->from($this->_name)
                     ->where('is_active = ?', true)
                     ->order('created_at DESC'));

                $paginator = new Zend_Paginator($adapter);
                $paginator->setCurrentPageNumber($page);
         return $paginator->setItemCountPerPage($item_per_page);
                
       
    }
    






}