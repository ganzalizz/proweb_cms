<?php


class Templates extends Zend_Db_Table
{
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

   



   

    private $_Paths = null;

    /**
     * тут хранятся значения главных тэгов для таблицы
     * @var array
     */
    private $_main_tags = null;


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
    
    public function getCount()
    {
        $select = $this->select()
                       ->from($this->_name, new Zend_Db_Expr('COUNT(id)'));
        return $this->fetchRow($select);
    }
    
    
    
}
