<?php

/*
CREATE TABLE `site_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `login` varchar(255) NOT NULL DEFAULT '',
  `pass` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `adress` text,
  `mobile_phone` varchar(255) DEFAULT '',
  `phone` varchar(255) DEFAULT '',
  `gender` tinyint(1) DEFAULT '0' COMMENT 'пол',
  `birthdate` date DEFAULT '0000-00-00' COMMENT 'дата рождения',
  `subscribe` tinyint(1) DEFAULT '0',
  `active` tinyint(1) DEFAULT '0',
  `role` varchar(255) DEFAULT '',
  `added` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251

*/


class SiteUsers extends Zend_Db_Table {

    protected $_name = 'site_users';
    protected $_primary = array('id');
    protected static $_instance = null;

    /**
     * Singleton instance
     *
     * @return SiteUsers
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    /**
     * проверка существует ли запись с указанным полем
     * @param string $param
     * @param string $value
     * @param int $id
     * @return boolean
     */
    public function checkField($param, $value, $id=null) {
        $row  = $this->fetchRow("$param='$value' AND id!='".(int)$id."'");
        if (!is_null($row)) {
            return true;
        }
        return false;
    }


    /**
     * валидаторы
     *
     * @return array
     */
    public function getValidators() {
        $messages = array_merge_recursive(Configurator::getConfig('messages')->Other->toArray(), Configurator::getConfig('messages')->email->toArray());

        $validators = array(
                'Имя' => array(
                        'presence'=>'required',  // обязательный параметр
                        Zend_Filter_Input::FIELDS =>'first_name',
                ),
                'Фамилия' => array(
                        'allowEmpty' => true,  // не обязательный параметр
                        Zend_Filter_Input::FIELDS =>'last_name',
                ),

                'Логин' => array(
                        array('stringLength', 5, 20),
                        'presence'=>'required',  // обязательный параметр
                        Zend_Filter_Input::FIELDS =>'login',
                        'messages' => array(
                                array(
                                        Zend_Validate_StringLength::TOO_LONG => $messages[Zend_Validate_StringLength::TOO_LONG],
                                        Zend_Validate_StringLength::TOO_SHORT => $messages[Zend_Validate_StringLength::TOO_SHORT]

                                )
                        )
                ),
                'Пароль' => array(
                        array('stringLength', 5, 20),
                        'presence'=>'required',  // обязательный параметр
                        Zend_Filter_Input::FIELDS =>'password',
                        'messages' => array(
                                array(
                                        Zend_Validate_StringLength::TOO_LONG => $messages[Zend_Validate_StringLength::TOO_LONG],
                                        Zend_Validate_StringLength::TOO_SHORT => $messages[Zend_Validate_StringLength::TOO_SHORT]

                                )
                        )
                ),

                'Улица'=>array(
                        'presence'=>'required',
                        Zend_Filter_Input::FIELDS =>'street',
                ),
                'Дом'=>array(
                        'presence'=>'required',
                        Zend_Filter_Input::FIELDS =>'house',
                ),
                'Корпус'=>array(
                        'allowEmpty' => true,  // не обязательный параметр
                        Zend_Filter_Input::FIELDS =>'house_block',
                ),
                'Квартира'=>array(
                        'allowEmpty' => true,  // не обязательный параметр
                        Zend_Filter_Input::FIELDS =>'flat',
                ),
                'Телефон'=>array(
                        'presence'=>'required',
                        Zend_Filter_Input::FIELDS =>'mobile_phone',
                ),
                'Дата рожения'=>array(
                        array('Date', 'd.m.Y'),
                        'allowEmpty' => true,  // не обязательный параметр
                        Zend_Filter_Input::FIELDS =>'birthdate',
                        'messages' => array(
                                array(
                                        Zend_Validate_Date::INVALID_DATE => $messages[Zend_Validate_Date::INVALID_DATE],
                                        Zend_Validate_Date::FALSEFORMAT => $messages[Zend_Validate_Date::FALSEFORMAT],
                                        Zend_Validate_Date::INVALID_DATE => $messages[Zend_Validate_Date::INVALID_DATE],

                                )
                        )
                ),


                'Электронная почта' => array(
                        'EmailAddress',
                        'presence'=>'required',  //обязательный параметр
                        Zend_Filter_Input::FIELDS =>'email',
                        'messages' => array(
                                array(
                                        Zend_Validate_EmailAddress::INVALID_FORMAT => $messages[Zend_Validate_EmailAddress::INVALID_FORMAT],
                                        Zend_Validate_EmailAddress::INVALID => $messages[Zend_Validate_EmailAddress::INVALID]
                                ),
                        )
                ),

                'subscribe'=>array(
                        'allowEmpty' => true,  // не обязательный параметр
                        Zend_Filter_Input::FIELDS =>'subscribe'

                ),
                'regular_customer'=>array(
                        'allowEmpty' => true,  // не обязательный параметр
                        Zend_Filter_Input::FIELDS =>'regular_customer'

                ),
                'discount'=>array(
                        'allowEmpty' => true,  // не обязательный параметр
                        Zend_Filter_Input::FIELDS =>'discount'

                ),
                'Условия регистрации'=>array(
                        'presence'=>'required',
                        Zend_Filter_Input::FIELDS =>'conditions'

                ),
                'gender'=>array(
                        'allowEmpty' => true,  // не обязательный параметр
                        'default' =>1,
                        Zend_Filter_Input::FIELDS =>'gender'

                ),
                'added'=>array(
                        'allowEmpty' => true,  // не обязательный параметр
                        'default' => date('Y-m-d H:i:s'),
                ),
                'active'=>array(
                        'default' =>1,
                ),

                'role'=>array(
                        'default' =>'power_user',
                ),


        );


        return $validators;
    }




}