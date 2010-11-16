<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Ext_Form extends Zend_Form
{
    /**
     * Инициализация формы
     * 
     * return void
     */  
    public function init()
    {
        // Вызов родительского метода
        parent::init();
        // Создаем объект переводчика, он нам необходим для перевода сообщений об ошибках.
        // В качестве адаптера используется php массив
       
        $translator = new Zend_Translate('array', Zend_Registry::get('config')->path->languages . 'errors.php');
        //$translator = new Zend_Translate_Adapter_Array(Zend_Registry::get('config')->path->languages . 'errors.php');
        // Задаем объект переводчика для формы
        $this->setTranslator($translator);
        
        /* Задаем префиксы для самописных элементов, валидаторов, фильтров и декораторов.
           Благодаря этому Zend_Form будет знать где искать наши самописные элементы */
        $this->addElementPrefixPath('Ext_Form_Validate', 'Form/Validate/', 'validate');
        $this->addElementPrefixPath('Ext_Form_Filter', 'Form/Filter/', 'filter');
        $this->addElementPrefixPath('Ext_Form_Decorator', 'Form/Decorator/', 'decorator');
        //$this->addElementPrefixPath('Ext_Form_Element', 'Form/Element/','element');
    }
    
}

