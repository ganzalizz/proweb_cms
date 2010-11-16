<?php
/**
 * News main class
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
    
     const TRAFFIC_LIGHTS_RED = 1;
     const TRAFFIC_LIGHTS_YELLOW = 2;
     const TRAFFIC_LIGHTS_GREEN = 3;
     
     public static $traffic_light = array(
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
     * Dependent tables.
     *
     * @var array
     */
    protected $_dependentTables = array(

            ) ;



    /**
     * Reference map.
     *
     * @var array
     */
    protected $_referenceMap = array(
            'Pages' => array(
                            'columns'           => array('id_page'),
                            'refTableClass'     => 'Pages',
                            'refColumns'        => array('id'),
                            'onDelete'          => self::CASCADE,
                            'onUpdate'          => self::RESTRICT
            )
            ) ;

    /**
     * Class to use for rows.
     *
     * @var string
     */
    protected $_rowClass = "Articles_Row" ;

    /**
     * Class to use for row sets.
     *
     * @var string
     */
    protected $_rowsetClass = "Articles_Rowset" ;


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
        $sql = "SELECT	 COUNT(*) AS count FROM site_articles WHERE $where";
        return $this->getAdapter()->fetchOne($sql);
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
                    $this->getAdapter()->quoteInto('pub= ?',1),
                    $this->getAdapter()->quoteInto('id_page= ?',(int)$page_id)
            );
            //$where =array( $this->getAdapter()->quoteInto('pub= ?',1));
        }

        $order =array( $this->getAdapter()->quoteInto('created_at DESC',null),
                $this->getAdapter()->quoteInto('name ASC',null)
        );
        return $this->fetchAll($where,$order,$count,$ofset);
    }


    /**
     * Добавить статью
     * @param  $data
     * @return $id
     */
    public function addArticle($data) {
        if (!isset($data['created_at'])) {
            $data['created_at'] =new Zend_Db_Expr('NOW()'); //date("d-m-Y H:i:s");
        }

        if (isset($data['pub']) && $data['pub']==1) {
            $data['pub_date'] =new Zend_Db_Expr('NOW()'); //date("d-m-Y H:i:s");
        }
        $id=$this->createRow()->setFromArray($data)->save();
        return $id;
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


    public function pubArticle($id) {
        $array = array('pub'=>1,'pub_date'=>new Zend_Db_Expr('NOW()'));
        $new = $this->find($id)->current();
        $new->setFromArray(array_intersect_key($array, $new->toArray()));
        $new->save();

    }
    /*
    *заблокировать статью
    *@param $id int
    *
    */
    public function unpubArticle($id) {
        $array = array('pub'=>0);
        $new = $this->find($id)->current();
        $new->setFromArray(array_intersect_key($array, $new->toArray()));
        $new->save();
    }
    /*
    *Редактирование статьи
    *@param $data array
    *@param $id int
    *
    */
public function editArticle($data,$id) {

$new = $this->find($id)->current();
$new->setFromArray(array_intersect_key($data, $new->toArray()));
$new->save();
}



    /*
	*копирование статьи
	*@param $id int
	*
    */
    public function CopyArticle($id) {

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
     * Удалить статью
     * @param <type> $id
     */
    public function deleteArticle($id) {
        $new = $this->find($id)->current();
        if($new!=null) $new->delete();
    }
    
    /*
    *сделать статью главной (для отображения на главной странице)
    *@param $id int
    *
    */
    public function setMainArticle($id) {
        $where = $this->getAdapter()->quoteInto('id= ?',$id);
        $data=array('main'=>1);
        return $this->update($data,$where);
    }

    public function unsetMainArticle($id) {
        $where = $this->getAdapter()->quoteInto('id= ?',$id);
        $data=array('main'=>0);
        return $this->update($data,$where);
    }

    /*
    *возвращает все статьи устанавленные как главные и опубликованные
    * @param array articles
    *
    */
    public function getMain($type='articles', $limit=0) {
        $select = new Zend_Db_Select($this->getAdapter());
        $select ->where($this->getAdapter()->quoteInto('pub= ?',1))
                ->where($this->getAdapter()->quoteInto('main= ?',1))
                ->where($this->getAdapter()->quoteInto('type= ?',$type))
                ->from($this->_name)
                ->order($this->getAdapter()->quoteInto('RAND()', null))
                ->limit($limit);
        return $this->getAdapter()->query($select);
    }

    /*
    * возвращает все статьи устанавленные как опубликованные
    * @param array articles
    *
    */
    public function getActiveArticles($type='articles', $limit=0) {
        $select = new Zend_Db_Select($this->getAdapter());
        $select ->where($this->getAdapter()->quoteInto('pub= ?',1))
                ->where($this->getAdapter()->quoteInto('type= ?',$type))
                ->from($this->_name)
                ->order($this->getAdapter()->quoteInto('created_at DESC', null))
                ->limit($limit);
        return $this->getAdapter()->query($select);
    }
    
    /*
     *Выборка статьи по цвету светофора 
     * 
     * @param color int
     * @return mixed or false eswhere
     */
     public function getTrafficLightingByColor(){
         $ids = array(self::TRAFFIC_LIGHTS_RED,self::TRAFFIC_LIGHTS_YELLOW,self::TRAFFIC_LIGHTS_GREEN);
         $select = new Zend_Db_Select($this->getAdapter());
         $select->from($this->_name)
                ->where('pub = ?', 1)
                ->where('prizn IN (?)', $ids);
             
         $rez = $this->getAdapter()->query($select)->fetchAll();        

         if (!count($rez)) return false;
         else return $rez;    
     }
    

    public function search($search) {
        $dbAdapter = Zend_Registry::get('db');
        $sql = $dbAdapter->quoteInto("SELECT DISTINCT *, 'articles' AS TYPE FROM site_articles WHERE site_articles.name LIKE '%".$search."%'
		    OR site_news.intro LIKE '%".$search."%'
		 	OR site_news.content LIKE '%".$search."%'
		    AND site_articles.pub =1 ORDER BY site_articles.name ; ",null);
        $result = $dbAdapter->query($sql);
        return  $result->fetchAll();

    }
    /**
     * reindex table for Zend_search_lucence
     */
    public function reindex() {
        $index = new Ext_Search_Lucene(Ext_Search_Lucene::NEWS);
        //$pages_rowset = $this->fetchAll('published=1')
        $count = 10 ;
        $offset = 0 ;
        $this->setPaths();
        while( ( $rowset = $this->fetchAll( 'pub=1', null, $count, $offset ) ) && ( 0 < $rowset->count() ) ) {

            while( $rowset->valid() ) {
                $row = $rowset->current() ;
                //
                // Prepare document
                //
                if ($path = $this->getElemPath($row->id_page)) {
                    $doc = new Ext_Search_Lucene_Document();
                    $doc->setUrl($path .'/item/'.$row->id.'/');
                    $doc->setTitle($row->name);
                    $doc->setContent(strip_tags($row->content));
                    $doc->setId($row->id);

                    $index->addDocument( $doc ) ;
                }
                $rowset->next() ;
            }
            $offset += $count ;
        }

        $index->commit() ;
        return $index->numDocs();

    }

    /**
     * выбираем все родительские страницы
     */
    public function setPaths() {
        $sql = "SELECT DISTINCT id_page FROM $this->_name WHERE id_page>0";
        $page_ids = $this->getAdapter()->fetchCol($sql);
        if ($page_ids!=null) {
            $pages = Pages::getInstance()->fetchAll("id in (".implode(',', $page_ids).")");
            if ($pages->count()) {
                foreach ($pages as $page) {
                    $this->_Paths[$page->id]=$page->path;
                }
            }
        }
    }

    /**
     * находим путь к родительской странице элемента
     * @param int $id
     */
    public function getElemPath($id) {
        if (is_null($this->_Paths)) {
            $this->setPaths();
        }
        if (isset($this->_Paths[$id])) {
            return $this->_Paths[$id];
        }
        return false;
    }





}