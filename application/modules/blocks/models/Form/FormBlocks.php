<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Form_FormBlocks extends Ext_Form {
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

        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'admin/blocks/form.phtml'))));

        // Указываем метод формы
        $this->setMethod('post');



        $this->setAttrib('accept-charset', 'UTF-8');
        // Задаем атрибут class для формы
        $this->setAttrib('class', 'blocks');
        $this->setAttrib('enctype', 'multipart/form-data');


        $id = new Zend_Form_Element_Hidden('id');

        $this->addElement($id);

        $system_name = new Zend_Form_Element_Text('system_name', array(
                    'required' => true,
                    'label' => 'Системное имя',
                    'description' => 'используется для выборки блока из базы',
                    'maxlength' => '150',
                    'validators' => array(
                        array('Regex', false, array('/^[a-z0-9_-]{1,}$/'))
                    ),
                    'filters' => array('StringTrim')
                ));

        $system_name_validator = new Zend_Validate_Db_NoRecordExists(array(
                    'table' => 'site_blocks',
                    'field' => 'system_name'
                ));
        if (self::$_idRecord != null) {
            $system_name_validator->setExclude(array(
                'field' => 'id',
                'value' => self::$_idRecord
            ));
        }
        $system_name->addValidator($system_name_validator);

        $this->addElement($system_name);

        $title = new Zend_Form_Element_Text('title', array(
                    'required' => true,
                    'label' => 'Название',
                    'description' => 'от 5 до 150 символов',
                    'maxlength' => '150',
                    'validators' => array(
                        array('Alnum', true, array(true)),
                        array('StringLength', true, array(5, 150))
                    ),
                    'filters' => array('StringTrim')
                ));

        $this->addElement($title);

        $type = new Zend_Form_Element_Radio('type', array(
                    'label' => 'Тип блока',
                    'description' => 'html - использовать fckeditor, text - textarea',
                    'required' => true,
                    'multiOptions' => array(
                        'text' => 'text',
                        'html' => 'html'
                    ),
                    'decorators' => array(
                        array('ViewHelper'),
                        array('Errors')
                    )
                ));
        //$type->removeDecorator('label');
        $type->setSeparator(' ');

        $this->addElement($type);

        $priority = new Zend_Form_Element_Text('priority', array(
                    'required' => false,
                    'label' => 'Приоритет',
                    'maxlength' => '40',
                    'validators' => array(
                        array('Alnum', true, array(true)),
                        array('StringLength', true, array(1, 40))
                    ),
                    'filters' => array('StringTrim'),
                ));

        $this->addElement($priority);


        $active = new Zend_Form_Element_Checkbox('active', array(
                    'label' => 'Активность',
                    'description' => 'На сайте отображаются только активные элементы',
                    'filters' => array('Int')
                ));

        $this->addElement($active);

        $content = new Zend_Form_Element_Textarea('content', array(
                    'label' => 'Содержание',
                    'validators' => array(
                        array('StringLength', true, array(1, 60000))
                    ),
                    'decorators' => array(
                        array('ViewHelper'),
                        array('Errors')),
                    'filters' => array('StringTrim'),
                    'rows' => 10,
                    'cols' => 5
                ));
        $this->addElement($content);

        $this->addDisplayGroup(
                array('id', 'system_name', 'title', 'type', 'priority', 'active', 'content'), 'blockDataGroup',
                array(
                    'legend' => 'Блок'
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
