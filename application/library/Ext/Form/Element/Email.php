<?php

/*
 * Простой элемент формы Email наследник Zend_Form_Element_Text
 * 
 */


class Ext_Form_Element_Email extends Zend_Form_Element_Text
{
    public function init()
    {
        $this->setLabel('Email');
        $this->setAttrib('maxlength', 80);
        $this->addValidator('EmailAddress', true);
        $this->addFilter('StringTrim');
    }
}
