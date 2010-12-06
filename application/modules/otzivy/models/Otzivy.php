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

    /**
     * Константы для светофора.
     *
     * @var const
     */
    
     const OTZIV_OTZIV = 0;
     const OTZIV_PREDLOZENIE = 1 ;
     
     
     public static $otziv_vid = array(
         self::OTZIV_OTZIV => 'Отзыв',
         self::OTZIV_PREDLOZENIE => 'Предложение'
         
     );
    

    /**
     * The default table name.
     *
     * @var string
     */
    protected $_name = 'site_otzivy';

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

   



   

    private $_Paths = null;

    /**
     * тут хранятся значения главных тэгов для таблицы
     * @var array
     */
    private $_main_tags = null;


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

    public function getCount($where) {
        $sql = "SELECT	 COUNT(*) AS count FROM site_otzivy WHERE $where";
        return $this->getAdapter()->fetchOne($sql);
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

    /*
	*получение отзыв предложение  по $id
	*@param $id int
	*
    */
    public function getOtzivyById($id) {
        $where = $this->getAdapter()->quoteInto('id= ?',$id);
        return $this->fetchRow($where);
    }


    public function pubOtziv($id) {
        $otziv = $this->find($id)->current();
        $otziv->setFromArray(array_intersect_key($array, $otziv->toArray()));
        $otziv->save();

    }
    /*
    *заблокировать отзыв
    *@param $id int
    *
    */
    public function unpubOtziv($id) {
        $array = array('pub'=>0);
        $otziv = $this->find($id)->current();
        $otziv->setFromArray(array_intersect_key($array, $otziv->toArray()));
        $otziv->save();
    }
    /*
    *Редактирование отзыва
    *@param $data array
    *@param $id int
    *
    */
    public function editOtziv($data,$id) {

        $otziv = $this->find($id)->current();
        $otziv->setFromArray(array_intersect_key($data, $otziv->toArray()));
        $otziv->save();
    }



    /*
    *копирование отзыва
    *@param $id int
    *
    */
    public function CopyOtziv($id) {

        $otziv= $this->getOtzivById($id);
        $otziv = $otziv->toArray();
        $otziv['created_at'] = new Zend_Db_Expr('NOW()');
        $otziv['pub'] = $otziv['pub']==1?$otziv['pub']=0:$otziv['pub']; 
        unset($otziv['id']);
        return $this->insert($otziv);
    }

    /**
     * Удалить отзыв
     * @param <type> $id
     */
    public function deleteOtziv($id) {
        $otziv = $this->find($id)->current();
        if($otziv!=null) $otziv->delete();
    }
    
    /*
    *сделать отзыв главной (для отображения на главной странице)
    *@param $id int
    *
    */
    public function setMainOziv($id) {
        $where = $this->getAdapter()->quoteInto('id= ?',$id);
        $data=array('main'=>1);
        return $this->update($data,$where);
    }

    public function unsetMainOtziv($id) {
        $where = $this->getAdapter()->quoteInto('id= ?',$id);
        $data=array('main'=>0);
        return $this->update($data,$where);
    }

    /*
    *возвращает все озывы устанавленные как главные и опубликованные
    * @param array articles
    *
    */
    public function getMain($type='otzivy', $limit=0) {
        $select = new Zend_Db_Select($this->getAdapter());
        $select ->where($this->getAdapter()->quoteInto('pub= ?',1))
                ->where($this->getAdapter()->quoteInto('main= ?',1))
                ->where($this->getAdapter()->quoteInto('type= ?',$type))
                ->from($this->_name)
                ->order($this->getAdapter()->quoteInto('RAND()', null))
                ->limit($limit);
        return $this->getAdapter()->query($select);
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
    
  

    





}