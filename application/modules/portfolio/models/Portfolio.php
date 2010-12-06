<?php
/**
 * News main class
 * @author Vitaly
 * @version 1.0
 * @copyright www.proweb.by
 * @package roweb
 *
 */
class Portfolio extends Zend_Db_Table {

        

    /**
     * The default table name.
     *
     * @var string
     */
    protected $_name = 'site_portfolio';

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
    protected $_rowClass = "Portfolio_Row" ;

    /**
     * Class to use for row sets.
     *
     * @var string
     */
    protected $_rowsetClass = "Portfolio_Rowset" ;


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
        $sql = "SELECT	 COUNT(*) AS count FROM site_portfolio WHERE $where";
        return $this->getAdapter()->fetchOne($sql);
    }

    /*
	*Возвращает все опбликованные статьи
	*
    */
    public function getAllPub($page_id, $ofset = null, $count = null) {
        $where=array();
        if (is_array($page_id)) {
            $where[] =$this->getAdapter()->quoteInto('is_active= ?',1);
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
     * Добавить статью
     * @param  $data
     * @return $id
     */
    public function addPortfolio($data) {
        if (!isset($data['created_at'])) {
            $data['created_at'] =new Zend_Db_Expr('NOW()'); //date("d-m-Y H:i:s");
        }

        if (isset($data['is_active']) && $data['is_active']==1) {
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
    public function getPortfolioById($id) {
        $where = $this->getAdapter()->quoteInto('id= ?',$id);
        return $this->fetchRow($where);
    }


    public function pubPortfolio($id) {
        $array = array('is_active'=>1,'pub_date'=>new Zend_Db_Expr('NOW()'));
        $new = $this->find($id)->current();
        $new->setFromArray(array_intersect_key($array, $new->toArray()));
        $new->save();

    }
    /*
    *заблокировать статью
    *@param $id int
    *
    */
    public function unpubPortfolio($id) {
        $array = array('is_active'=>0);
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
public function editPortfolio($data,$id) {

$new = $this->find($id)->current();
$new->setFromArray(array_intersect_key($data, $new->toArray()));
$new->save();
}



    /*
	*копирование статьи
	*@param $id int
	*
    */
    public function CopyPortfolio($id) {

        $new= $this->getNewById($id);
        $new = $new->toArray();
        $new['url'] = $new['url'].'_copy'.rand(1,20);
        $new['created_at'] = new Zend_Db_Expr('NOW()');
        if ($new['is_active']==1) {
            $new['pub_date']=new Zend_Db_Expr('NOW()');
        }
        unset($new['id']);

        return $this->insert($new);
    }

    /**
     * Удалить статью
     * @param <type> $id
     */
    public function deletePortfolio($id) {
        $new = $this->find($id)->current();
        if($new!=null) $new->delete();
    }
    
    /*
    *сделать статью главной (для отображения на главной странице)
    *@param $id int
    *
    */
    public function setMainPortfolio($id) {
        $where = $this->getAdapter()->quoteInto('id= ?',$id);
        $data=array('main'=>1);
        return $this->update($data,$where);
    }

    public function unsetMainPortfolio($id) {
        $where = $this->getAdapter()->quoteInto('id= ?',$id);
        $data=array('main'=>0);
        return $this->update($data,$where);
    }

    /*
    *возвращает все статьи устанавленные как главные и опубликованные
    * @param array portfolio
    *
    */
    public function getMain($type='portfolio', $limit=0) {
        $select = new Zend_Db_Select($this->getAdapter());
        $select ->where($this->getAdapter()->quoteInto('is_active= ?',1))
                ->where($this->getAdapter()->quoteInto('main= ?',1))
                ->where($this->getAdapter()->quoteInto('type= ?',$type))
                ->from($this->_name)
                ->order($this->getAdapter()->quoteInto('RAND()', null))
                ->limit($limit);
        return $this->getAdapter()->query($select);
    }

    /*
    * возвращает все статьи устанавленные как опубликованные
    * @param array portfolio
    *
    */
    public function getActivePortfolio($year, $type='portfolio', $limit=0) {
        if ($year == 'all'){
            $select = new Zend_Db_Select($this->getAdapter());
            $select ->where($this->getAdapter()->quoteInto('is_active= ?',1))
                    ->where($this->getAdapter()->quoteInto('type= ?',$type))
                    ->from($this->_name)
                    ->order($this->getAdapter()->quoteInto('created_at DESC', null))
                    ->limit($limit);
        }
        else {
        
            $select = new Zend_Db_Select($this->getAdapter());
            $select ->where($this->getAdapter()->quoteInto('is_active= ?',1))
                ->where($this->getAdapter()->quoteInto('type= ?',$type))
                ->where($this->getAdapter()->quoteInto('YEAR(created_at)= ?',$year))    
                ->from($this->_name)
                ->order($this->getAdapter()->quoteInto('created_at DESC', null))
                ->limit($limit); 
            
        }    
        return $this->getAdapter()->query($select);
    }
    
    public function search($search) {
        $dbAdapter = Zend_Registry::get('db');
        $sql = $dbAdapter->quoteInto("SELECT DISTINCT *, 'portfolio' AS TYPE FROM site_portfolio WHERE site_portfolio.name LIKE '%".$search."%'
		    OR site_news.intro LIKE '%".$search."%'
		 	OR site_news.content LIKE '%".$search."%'
		    AND site_portfolio.is_active =1 ORDER BY site_portfolio.name ; ",null);
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
        while( ( $rowset = $this->fetchAll( 'is_active=1', null, $count, $offset ) ) && ( 0 < $rowset->count() ) ) {

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
    
    /*
     * Выборка годов из бд
     * 
     */
    public function getYearsForFilter()
    {
        $sql = "SELECT YEAR( created_at ) AS year
                FROM $this->_name
                GROUP BY YEAR
                ORDER BY year ASC";
        return $years = $this->getAdapter()->fetchCol($sql);
    }




}