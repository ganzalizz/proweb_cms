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
        
        // Указываем action формы
        //$helperUrl = new Zend_View_Helper_Url();
        //$this->setAction($helperUrl->url(array(), 'otzivy'));
        $this->setAction('otzivy');
        // Указываем метод формы
        $this->setMethod('post');
        
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
            'filters'     => array('StringTrim')
        ));
         

          

        $this->addElement($name);
        
       // $name->setName('name');
        
        $email = new Ext_Form_Element_Email('email', array(
            'required' => true
        ));
        
        $this->addElement($email);
          
                
        $prizn = new Zend_Form_Element_Radio('prizn', array(
            'label' => 'Тип сообщения',
            'multiOptions' => Otzivy::$otziv_vid
            
        ));
        
        $prizn->setSeparator(' ');
                
        $this->addElement($prizn); 
        
        $content = new Zend_Form_Element_Textarea('content', array(
            'label' => 'Сообщение',
            'required' => true,
            'rows' => '5',
            'cols' => '45',
            'validators' => array(
                array('StringLength', true, array(0, 5000))
            ),
            'filters' => array('StringTrim')
            
            
        ));
                
        $this->addElement($content);
        
       // $captcha = new Ext_Form_Element_CaptchaWord('captcha');
        $captcha = new Zend_Form_Element_Captcha('captcha', array(
            'label' => "Введите символы:",
            'captcha' => array(
                'captcha'   => 'Image', // Тип CAPTCHA
                'wordLen'   => 4,       // Количество генерируемых символов
                'width'     => 200,     // Ширина изображения
                'timeout'   => 120,     // Время жизни сессии хранящей символы
                'expiration'=> 300,     // Время жизни изображения в файловой системе
                'font'      => Zend_Registry::get('config')->path->rootPublic . 'fonts/arial.ttf', // Путь к шрифту
                'imgDir'    => Zend_Registry::get('config')->path->rootPublic . 'img/captcha/', // Путь к изобр.
                'imgUrl'    => '/img/captcha/', // Адрес папки с изображениями
                'gcFreq'    => 5        // Частота вызова сборщика мусора
            ),
        ));
                
        $this->addElement($captcha);
        
         
        
        // Кнопка Submit
        $submit = new Zend_Form_Element_Submit('submit', array(
            'label'       => 'Послать',
        ));
        
        $this->addElement($submit);

             
        
   
        
    }
}
?>
