<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Form_FormOtzivy extends Ext_Form
{
    public function init()
    {
        parent::init();
        
       
        $this->setMethod('post');
        
        $this->setDecorators( array( array( 'ViewScript', array( 'viewScript' => 'otzivy/formotzivy.phtml' ) ) ) );
        
        // Задаем атрибут class для формы
        $this->setAttrib('class', 'otzivy');
        
        $name = new Zend_Form_Element_Text('name', array(
            'required' => true,
            'label' => 'Имя',
            'maxlength'   => '30',
            'validators'  => array(
                array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 30))
             ),
            'decorators' => array(
				array( 'ViewHelper' ), 
				array( 'Errors' )
             ), 
            'filters'     => array('StringTrim')
        ));
        $name->setAttrib('class', 'input');
       // $name->addDecorator('htmlTag', array('tag' => 'div', 'class'=>'item'));
        //$name->removeDecorator('label');
        
        
         

          

        $this->addElement($name);
        
       // $name->setName('name');
        
        $email = new Ext_Form_Element_Email('email', array(
            'required' => true,
        	'decorators' => array(        		
				array( 'ViewHelper' ),
				array( 'Errors' )
				
             )
        ));
        $email->setAttrib('class', 'input');
        $this->addElement($email);
          
                
        $prizn = new Zend_Form_Element_Radio('prizn', array(
            'label' => 'Тип сообщения',
        	'required' => true,
            'multiOptions' => Otzivy::$otziv_vid,
        	'decorators' => array(
				array( 'ViewHelper' ), 
				array( 'Errors' )
             )
            
        ));
        $prizn->removeDecorator('label');        
        $prizn->setSeparator(' ');        
                
        $this->addElement($prizn); 
        
        $content = new Zend_Form_Element_Textarea('content', array(
            'label' => 'Сообщение',
            'required' => true,
            //'rows' => '5',
           // 'cols' => '45',
            'validators' => array(
                array('StringLength', true, array(0, 5000))
            ),
            'decorators' => array(
				array( 'ViewHelper' ), 
				array( 'Errors' )
             ), 
            'filters' => array('StringTrim')
            
            
        ));
        $content->setAttrib('class', 'textarea');        
        $this->addElement($content);
        
       // $captcha = new Ext_Form_Element_CaptchaWord('captcha');
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
?>
