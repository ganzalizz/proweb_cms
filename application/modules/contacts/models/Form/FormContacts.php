<?php

class Form_FormContacts extends Ext_Form
{
    public function init()
    {
        $name = new Zend_Form_Element_Text('name', array(
            'label' => 'Ваше имя',
            'required' => true,
            'validators'  => array(
                array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 40))
             ),
            'filters'     => array('StringTrim')
        ));
        $this->addElement($name);
        
        $email = new Ext_Form_Element_Email('email', array(
            'label' => 'Email',
            'required' => true
        ));
        $this->addElement($email);
        
        $subject = new Zend_Form_Element_Text('subject', array(
            'label' => 'Тема',
            'required' => true,
            'validators'  => array(
                array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 80))
             ),
            'filters'     => array('StringTrim')
        ));
        $this->addElement($subject);
        
        $message = new Zend_Form_Element_Textarea('message', array(
            'label' => 'Сообщение',
            'required' => true,
            'validators'  => array(
                array('StringLength', true, array(5, 1000))
             ),
            'filters'     => array('StringTrim')
        ));
        $this->addElement($message);
        
        
    }
}
