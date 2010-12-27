<?php

class Form_FormContacts extends Ext_Form
{
    public function init()
    {
        $this->setMethod('post');
        
        $this->setDecorators( array( array( 'ViewScript', array( 'viewScript' => 'contacts/formcontacts.phtml' ) ) ) );
        
        $this->setAttrib('class', 'contacts');
        
        $name = new Zend_Form_Element_Text('name', array(
            'label' => 'Ваше имя',
            'required' => true,
            'validators'  => array(
                array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 40))
             ),
            'decorators' => array(
				array( 'ViewHelper' ), 
				array( 'Errors' )),
            'filters'     => array('StringTrim')
        ));
        $name->setAttrib('class', 'input');
        $this->addElement($name);
        
        $email = new Ext_Form_Element_Email('email', array(
            'label' => 'Email',
            'required' => true,
            'decorators' => array(        		
				array( 'ViewHelper' ),
				array( 'Errors' ))
        ));
        $email->setAttrib('class', 'input');
        $this->addElement($email);
        
        $subject = new Zend_Form_Element_Text('subject', array(
            'label' => 'Тема',
            'required' => true,
            'validators'  => array(
                array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 80))
             ),
            'decorators' => array(
				array( 'ViewHelper' ), 
				array( 'Errors' )),
            'filters'     => array('StringTrim'),
            //'value' => 'Заказ шаблона: название шаблона (Стоимость: 100)'
        ));
        $subject->setAttrib('class', 'input');

        //$subject->setAttrib('disabled', 'disabled');

        $this->addElement($subject);
        
        $message = new Zend_Form_Element_Textarea('message', array(
            'label' => 'Сообщение',
            'required' => true,
            'validators'  => array(
                array('StringLength', true, array(5, 1000))
             ),
            'decorators' => array(
				array( 'ViewHelper' ), 
				array( 'Errors' )),
            'filters'     => array('StringTrim'),
            'rows'        => 10
        ));
        $message->setAttrib('class', 'input');
        $this->addElement($message);
        
         $captcha = new Zend_Form_Element_Captcha('captcha', array(
            'label' => "Введите символы:",
            'captcha' => array(
                'captcha'   => 'Image', // Тип CAPTCHA
                'wordLen'   => 4,       // Количество генерируемых символов
                'width'     => 100,     // Ширина изображения                
                'height'    => 40,     // Ширина изображения                
                'timeout'   => 120,     // Время жизни сессии хранящей символы
                'expiration'=> 300,     // Время жизни изображения в файловой системе
                'font'      => Zend_Registry::get('config')->path->rootPublic . 'fonts/arial.ttf', // Путь к шрифту                
                'fontSize'  => 13,                 
                'imgDir'    => Zend_Registry::get('config')->path->rootPublic . 'img/captcha/', // Путь к изобр.
                'imgUrl'    => '/img/captcha/', // Адрес папки с изображениями
                'gcFreq'    => 5,        // Частота вызова сборщика мусора
                'dotNoiseLevel' => 5, 	 // частота зашумления картинки точками, количество точек
                'lineNoiseLevel' => 0 	 // частота зашумления картинки линиями, количество линий
        		
            )
        ));        
        $captcha->removeDecorator('label');
        $captcha->addDecorator( 'HtmlTag', array( 'tag' => 'span', 'class'=>'captcha' ));    
            
        $this->addElement($captcha);
        
         
        /*
        // Кнопка Submit
        $submit = new Zend_Form_Element_Submit('submit', array(
            'label'       => 'Послать',
        ));*/
        
        $submit = new Zend_Form_Element_Image('submit');
        $submit->setAttrib('src', '/img/send.gif');
        $submit->setDecorators(array( 'ViewHelper' ), array('HtmlTag', array( 'tag' => 'span' )) );
        
        $this->addElement($submit);
        
        
    }
}
