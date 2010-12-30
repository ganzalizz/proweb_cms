<?php

class Users extends Zend_Db_Table {

    protected $_name = 'users';
    protected $_primary = array('id');
    protected static $_instance = null;
    /**
     * Валидаторы
     *
     * @var array
     */
    protected $_validators = array(
        'Роль' => array(
            'presence' => 'required', // обязательный параметр
            Zend_Filter_Input::FIELDS => 'role',
        ),
        'Имя' => array(
            'presence' => 'required', // обязательный параметр
            Zend_Filter_Input::FIELDS => 'firstName',
        ),
        'Фамилия' => array(
            'allowEmpty' => true, // не обязательный параметр
            Zend_Filter_Input::FIELDS => 'lastName',
        ),
        'Логин' => array(
            'presence' => 'required', // обязательный параметр
            Zend_Filter_Input::FIELDS => 'username',
        ),
        'Пароль' => array(
            'presence' => 'required', //обязательный параметр
            Zend_Filter_Input::FIELDS => 'password'
        ),
        'Email' => array(
            'EmailAddress',
            'presence' => 'required', //обязательный параметр
            Zend_Filter_Input::FIELDS => 'email'
        ),
        'Активен' => array(
            'allowEmpty' => true, // не обязательный параметр
            // 'default'=>0,
            Zend_Filter_Input::FIELDS => 'activity'
        ),
        'Получать уведомления' => array(
            'allowEmpty' => true, // не обязательный параметр
            //'default'=>0,
            Zend_Filter_Input::FIELDS => 'send_message'
        )
    );

    /**
     * Singleton instance
     * @return Users
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * получение списка всех профилей
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
     * валидаторы
     *
     * @return array
     */
    public function getValidators() {
        return $this->_validators;
    }

    public function getUsers() {
        $cur_user = Security::getInstance()->getUser();
        if ($cur_user != null && $cur_user->deletable != 0) {
            $where = $this->getAdapter()->quoteInto('deletable = ?', '1');
        } else {
            $where = null;
        }
        return $this->fetchAll($where);
    }

    public function setActivity($id, $value) {
        $where = $this->getAdapter()->quoteInto('id = ?', $id);
        $this->update(array('activity' => $value), $where);

        return true;
    }

    public function deleteUser($id) {
        $where = $this->getAdapter()->quoteInto('id = ?', $id);
        $this->delete($where);

        return true;
    }

    public function getUser($id) {
        $where = $this->getAdapter()->quoteInto('id = ?', $id);
        $user = $this->fetchRow($where);

        return $user;
    }

    public function getUsersToSendMail() {
        $where = $this->getAdapter()->quoteInto("send_message = ?", 1);

        return $this->fetchAll($where);
    }

    /**
     * получение юзеров по роли 
     * @param string $role
     * @return Zend_Db_Table_Rowset       
     */
    public function getUsersByRole($role) {
        $select = $this->select();
        $select->where("role= ?", $role);
        $select->order("firstName");
        return $this->fetchAll($select);
    }

    /**
     * @name addUser Добавить профиль
     * @param Zend_Db_Table_Row $row
     * @param  array $data
     *
     * @return Zend_Db_Table_Row inserted news
     */
    public function addUser($row, $data) {

        unset($data['id']);
        $row->setFromArray($data)->save();
        return $row;
    }

    /**
     * редактирование шаблона
     * @param Zend_Db_Table_Row $row
     * @param array $data
     * @return Zend_Db_Table_Row
     */
    public function editUser($row, $data)
    {

        unset($data['id']);
        $row->setFromArray($data)->save();

        return $row;

    }

}