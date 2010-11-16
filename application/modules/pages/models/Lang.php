<?php

class Lang extends Zend_Db_Table {

    protected $_name = 'site_languages';
    protected $_primary = array('id');
    protected static $_instance = null;

    /**
     * Получение объект класса
     *
     * @return Lang
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Добавление новой языковой версии
     *
     * @param array $data
     */
    public function addLang($data) {
        $this->insert($data);
        Loader::loadPublicModel('Pages');
        Pages::getInstance()->addVersion($data['name']);
    }

    /**
     * Получение языковой версии
     *
     * @param string $name название языка
     * @return object об
     */
    public function getVersion($name) {
        return $this->fetchRow($this->getAdapter()->quoteInto('name = ?', $name));
    }

}