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
            );
    /**
     * Reference map.
     *
     * @var array
     */
    protected $_referenceMap = array();

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

    public function getAll($onpage, $page) {
        $select = $this->select()
                        ->from($this->_name, array('*'))
                        ->order('date_project DESC');
        return $this->getPaginator($select, $onpage, $page);
    }

    /**
     *
     * @param Zend_Db_Table_Select $select
     * @param int $item_per_page
     * @param int $page
     * @return Zend_Paginator
     */
    private function getPaginator($select, $item_per_page, $page) {
        $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        return $paginator->setItemCountPerPage($item_per_page);
    }

    /**
     * @name addPortfolio Добавить запись портфолио
     * @param Zend_Db_Table_Row $row
     * @param  array $data
     *
     * @return Zend_Db_Table_Row inserted news
     */
    public function addPortfolio($row, $data)
    {

        $data['created_at'] = (!isset($data['created_at'])) ? new Zend_Db_Expr('NOW()'): date('Y-m-d', strtotime($data['created_at']));
        $data['pub_date'] = (!isset($data['pub_date'])) ? new Zend_Db_Expr('NOW()') : date('Y-m-d', strtotime($data['pub_date']));
        $data['date_project'] = (!isset($data['created_at'])) ? new Zend_Db_Expr('NOW()'): date('Y-m-d', strtotime($data['date_project']));
        unset($data['id']);
        $row->setFromArray($data);
        $row->save();
        return $row;

    }

    /**
     * редактирование портфолио
     * @param Zend_Db_Table_Row $row
     * @param array $data
     * @return Zend_Db_Table_Row
     */
    public function editPortfolio($row, $data)
    {

        unset($data['id']);
        if ($data['pub_date']!=''){
        	$data['pub_date'] = date('Y-m-d', strtotime($data['pub_date']));
        }
        if ($data['created_at']!=''){
        	$data['created_at'] = date('Y-m-d', strtotime($data['created_at']));
        }
        if ($data['date_project']!=''){
        	$data['date_project'] = date('Y-m-d', strtotime($data['date_project']));
        }
        $row->setFromArray($data)->save();

        return $row;

    }

    /*
     * Выборка годов из бд
     *
     */
    public function getYearsForFilter()
    {

        $sql = "SELECT YEAR( date_project ) AS year
                FROM $this->_name
                GROUP BY YEAR
                ORDER BY year ASC";
        return $years = $this->getAdapter()->fetchCol($sql);
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
                    ->from($this->_name)
                    ->order($this->getAdapter()->quoteInto('date_project DESC', null))
                    ->limit($limit);
        }
        else {
            $select = new Zend_Db_Select($this->getAdapter());
            $select ->where($this->getAdapter()->quoteInto('is_active= ?',1))
                ->where($this->getAdapter()->quoteInto('YEAR(date_project)= ?',$year))
                ->from($this->_name)
                ->order($this->getAdapter()->quoteInto('date_project DESC', null))
                ->limit($limit);

        }
        return $this->getAdapter()->query($select);
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

    /**
     * @name getPrevPortfolio($id) получение предыдущего элемента портфолио
     *
     *@param $id int
     *
     *@return mixed|null
     *
    */
    public function getPrevPortfolio($id)
    {
        $select = $this->select()
                        ->from($this->_name, array('*'))
                        ->where('is_active = ?', 1)
                        ->where('id < ?', $id)
                        ->order('id DESC')
                        ->limit(1);
        return $this->fetchRow($select);
    }

    /**
     * @name getNextPortfolio($id) получение следующего элемента портфолио
     *
     *@param $id int
     *
     *@return mixed|null
     *
    */
    public function getNextPortfolio($id)
    {
        $select = $this->select()
                        ->from($this->_name, array('*'))
                        ->where('is_active = ?', 1)
                        ->where('id > ?', $id)
                        ->order('id')
                        ->limit(1);
        return $this->fetchRow($select);
    }

    /**
     * @name getTemplateByUrl($url) получение элемента портфолио по $url
     *
     * @param $url int
     * @return mixed|null
     */
    public function getPortfolioByUrl($url) {
        $select = $this->select()
                        ->from($this->_name, array('*'))
                        ->where('is_active = ?', 1)
                        ->where('url = ?', $url);
        return $this->fetchRow($select);
    }

    /**
     * @name getPortfolioCount() получение кол-ва эл-тов портфолио
     *
     * @return int
     */
    public function getPortfolioCount() {
        $select = $this->select()
                        ->from($this->_name,'COUNT(*) AS num')
                        ->where('is_active = ?', 1);
        return $this->fetchRow($select)->num;
    }

}