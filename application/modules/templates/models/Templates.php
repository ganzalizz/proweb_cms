<?php

/**
 * Templates main class
 * @author Sergey
 * @version 1.0
 * @copyright www.proweb.by
 * @package proweb
 *
 */
class Templates extends Zend_Db_Table {

    /**
     * The default table name.
     *
     * @var string
     */
    protected $_name = 'site_templates';
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
     * @return Templates
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * получение списка всех шаблонов
     * используется для админки
     * @param int $onpage
     * @param int $page
     * @return Zend_Paginator
     */
    public function getAll($onpage, $page) {
        $select = $this->select()
                        ->from($this->_name, array('*'))
                        ->order('id ASC');
        return $this->getPaginator($select, $onpage, $page);
    }

    /**
     * @name getTemplatesPaginator получить TemplatesPaginator
     *
     * @param int $item_per_page
     * @param int $page
     * @return Zend_Paginator
     */
    public function getTemplatesPaginator($item_per_page, $page) {
        $adapter = new Zend_Paginator_Adapter_DbTableSelect(
                                $this->select()
                                ->from($this->_name, array('*'))
                                ->where('is_active = ?', true)
                                ->order('id ASC'));

        $paginator = new Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        return $paginator->setItemCountPerPage($item_per_page);
    }

    /**
     * @name getTemplateByUrl($url) получение шаблона по $url
     *
     * @param $url int
     * @return mixed|null
     */
    public function getTemplateByUrl($url) {
        $select = $this->select()
                        ->from($this->_name, array('*'))
                        ->where('is_active = ?', 1)
                        ->where('url = ?', $url);
        return $this->fetchRow($select);
    }

    /**
     * @name getNewById($id) получение новости по $id
     *
     *@param $id int
     *
     *@return mixed|null
     *
    */
    public function getTemplateById($id)
    {
        $select = $this->select()
                        ->from($this->_name, array('*'))
                        ->where('is_active = ?', 1)
                        ->where('id = ?', $id);
        return $this->fetchRow($select);
    }

    /**
     * @name getPrevTemplate($id) получение предыдущего шаблона
     *
     *@param $id int
     *
     *@return mixed|null
     *
    */
    public function getPrevTemplate($id)
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
     * @name getNextTemplate($id) получение следующего шаблона
     *
     *@param $id int
     *
     *@return mixed|null
     *
    */
    public function getNextTemplate($id)
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
     * @name addCountViews($id) инкремент кол-ва просмотров
     *
     * @param $id int
     * @return mixed|null
     */
    public function addCountViews($id) {
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
    private function getPaginator($select, $item_per_page, $page) {
        $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        return $paginator->setItemCountPerPage($item_per_page);
    }

    /**
     * @name addTemplate Добавить шаблон
     * @param Zend_Db_Table_Row $row
     * @param  array $data
     *
     * @return Zend_Db_Table_Row inserted news
     */
    public function addTemplate($row, $data) {

        unset($data['id']);
        $row->setFromArray($data);
        $row->save();
        return $row;
    }

    /**
     * редактирование шаблона
     * @param Zend_Db_Table_Row $row
     * @param array $data
     * @return Zend_Db_Table_Row
     */
    public function editTemplate($row, $data)
    {

        unset($data['id']);
        $row->setFromArray($data)->save();

        return $row;

    }

}
