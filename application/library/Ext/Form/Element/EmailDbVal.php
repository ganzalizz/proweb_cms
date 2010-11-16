<?php

/*
 * Элемент формы Email наследник Zend_Form_Element_Text
 * с проверкой существования email и user в базе данных
 * 
 */
class Ext_Form_Element_EmailDbVal extends Zend_Form_Element_Text
{
    public function init()
    {
        $this->setLabel('Email');
        $this->setAttrib('maxlength', 80);
        $this->addValidator('EmailAddress', true);
        //TODO: Сделать валидатор 'NoDbRecordExists
        $this->addValidator('NoDbRecordExists', true, array('users', 'email'));
        $this->addFilter('StringTrim');
    }
}
