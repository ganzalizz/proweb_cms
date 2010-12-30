<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Form_FormUsers extends Ext_Form {
    const DIR_TMP = '/uploads/';
    const MAX_FILE_SIZE = 5120000;

    private static $_idRecord = null;

    /**
     * установка id записи в базе 
     * для проверки уникальность поля url
     * @param $id
     */
    public static function setRecordId($id) {
        return self::$_idRecord = $id;
    }

    public function init() {
        parent::init();

        //$this->setName('news');
        $this->setAction('');

        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'users/form.phtml'))));

        // Указываем метод формы
        $this->setMethod('post');



        $this->setAttrib('accept-charset', 'UTF-8');
        // Задаем атрибут class для формы
        $this->setAttrib('class', 'templates');
        $this->setAttrib('enctype', 'multipart/form-data');


        $id = new Zend_Form_Element_Hidden('id');

        $this->addElement($id);

        $role = new Zend_Form_Element_Select('role', array('label' => 'Роль'));
        $roles_list = Roles::getInstance()->getRolesForSelect();
        $role->setMultiOptions($roles_list);
        $role->setValue($roles_list['admin']);

        $this->addElement($role);

        $firstName = new Zend_Form_Element_Text('firstName', array(
                    'required' => true,
                    'label' => 'Имя',
                    'description' => 'от 5 до 150 символов',
                    'maxlength' => '150',
                    'validators' => array(
                        array('Alnum', true, array(true)),
                        array('StringLength', true, array(5, 150))
                    ),
                    'filters' => array('StringTrim')
                ));

        $this->addElement($firstName);

        $lastName = new Zend_Form_Element_Text('lastName', array(
                    'required' => true,
                    'label' => 'Фамилия',
                    'description' => 'от 5 до 150 символов',
                    'maxlength' => '150',
                    'validators' => array(
                        array('Alnum', true, array(true)),
                        array('StringLength', true, array(5, 150))
                    ),
                    'filters' => array('StringTrim')
                ));

        $this->addElement($lastName);

        $email = new Zend_Form_Element_Text('email', array(
                    'required' => true,
                    'label' => 'E-mail',
                    'description' => 'от 5 до 150 символов',
                    'maxlength' => '150',
                    'filters' => array('StringTrim')
                ));

        $email_validator = new Zend_Validate_EmailAddress();
        $email->addValidator($email_validator);

        $this->addElement($email);

        $username = new Zend_Form_Element_Text('username', array(
                    'required' => true,
                    'label' => 'Логин',
                    'description' => 'от 5 до 150 символов',
                    'maxlength' => '150',
                    'validators' => array(
                        array('Alnum', true, array(true)),
                        array('StringLength', true, array(5, 150))
                    ),
                    'filters' => array('StringTrim')
                ));

        $username_validator = new Zend_Validate_Db_NoRecordExists(array(
                    'table' => 'users',
                    'field' => 'username'
                ));
        if (self::$_idRecord != null) {
            $username_validator->setExclude(array(
                'field' => 'id',
                'value' => self::$_idRecord
            ));
        }
        $username->addValidator($username_validator);

        $this->addElement($username);

        $activity = new Zend_Form_Element_Checkbox('activity', array(
                    'label' => 'Пользователь активен',
                    'filters' => array('Int')
                ));

        $this->addElement($activity);

        $send_message = new Zend_Form_Element_Checkbox('send_message', array(
                    'label' => 'Посылать уведомления',
                    'filters' => array('Int')
                ));

        $this->addElement($send_message);

        $password = new Zend_Form_Element_Text('password', array(
                    'required' => true,
                    'label' => 'Пароль',
                    'description' => 'от 5 до 150 символов',
                    'maxlength' => '150',
                    'validators' => array(
                        array('Alnum', true, array(true)),
                        array('StringLength', true, array(5, 150))
                    ),
                    'filters' => array('StringTrim')
                ));

        $this->addElement($password);

        $this->addDisplayGroup(
                array('role', 'firstName', 'lastName', 'email', 'activity', 'send_message', 'username', 'password'), 'usersPersonalData',
                array(
                    'legend' => 'Персональные данные администратора'
        ));
        
        $submit = new Zend_Form_Element_Button('submit', '<span><em>Сохранить</em></span>');
        $submit->setAttrib('escape', false);
        $submit->setAttrib('type', 'submit');
        //$submit->setAttrib('content', '<span><em>Сохранить</em></span>');
        $this->addElement($submit);

        foreach ($this->getElements() as $element) {

            if (!strpos($element->getType(), 'JQuery')) {
                $element->setDecorators(array(
                    array('ViewHelper'),
                    array('Errors')
                ));
            }
        }
    }

}
