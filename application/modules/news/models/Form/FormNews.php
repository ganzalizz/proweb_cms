<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Form_FormNews extends Ext_Form
{
     public function init()
    {
        parent::init();
        
       
        $this->setAction('');
        // Указываем метод формы
        $this->setMethod('post');
        
        // Задаем атрибут class для формы
        $this->setAttrib('class', 'news');
        
        $name = new Zend_Form_Element_Text('name', array(
            'required' => true,
            'label' => 'Название',
            'maxlength'   => '150',
            'validators'  => array(
                array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 150))
             ),
            'filters'     => array('StringTrim')
        ));
         
        $this->addElement($name);
        
       /* $teaser = new Zend_Form_Element_Textarea('teaser', array(
            'label' => 'Анонс',
            'required' => true,
            'rows' => '5',
            'cols' => '45',
            'validators' => array(
                array('StringLength', true, array(0, 1000))
            ),
            'filters' => array('StringTrim')
            
            
        ));*/
         $teaser = new Ext_Form_Element_CkEditor('teaser', array(
                    'label' => 'Анонс',
         			'description' => 'не более 1000 символов',
                    'required' => true,         			
                    'filters' => array('StringTrim')
                ));
        
        $this->addElement($teaser);
        
        $content = new Ext_Form_Element_CkEditor('content', array(
                    'label' => 'Новость',
                    'required' => true,
                    'filters' => array('StringTrim')
                ));
        
        $this->addElement($content);
        
        $link = new Zend_Form_Element_Text('link', array(
            'label' => 'Сылка на первоисточник',
            'maxlength'   => '255',
            'validators'  => array(
                array('Alnum', true, array(true)),
                array('StringLength', true, array(11, 255))
             ),
            'filters'     => array('StringTrim')
        ));
        
        $link->addValidator(new Ext_Form_Validate_UrlValidator());
        
        $this->addElement($link);
        
        $author = new Zend_Form_Element_Text('author', array(
            'required' => true,
            'label' => 'Автор новости',
            'maxlength'   => '150',
            'validators'  => array(
                array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 150))
             ),
            'filters'     => array('StringTrim')
        ));
        
       $this->addElement($author);
       
       //TODO: Date element
       
       $is_active = new Zend_Form_Element_Checkbox('is_active', array(
            'label'       => 'Новость активна',
            'filters'     => array('Int')
       ));
       
       $this->addElement($is_active);
       
       $is_main = new Zend_Form_Element_Checkbox('is_main', array(
            'label'       => 'Новость на главную',
            'filters'     => array('Int')
       ));
       
       $this->addElement($is_main);
       
       $is_hot = new Zend_Form_Element_Checkbox('is_hot', array(
            'label'       => 'Горячая новость',
            'filters'     => array('Int')
       ));
       
       $this->addElement($is_hot);
       
       $seo_title = $author = new Zend_Form_Element_Text('seo_title', array(
            'required' => true,
            'label' => 'Заголовок страницы',
            'maxlength'   => '150',
            'validators'  => array(
                array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 150))
             ),
            'filters'     => array('StringTrim')
        ));
       
       $this->addElement($seo_title);
       
       $seo_descriptions = $author = new Zend_Form_Element_Text('seo_descriptions', array(
            'required' => true,
            'label' => 'Описание страницы',
            'maxlength'   => '300',
            'validators'  => array(
                array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 300))
             ),
            'filters'     => array('StringTrim')
        ));
       
       $this->addElement($seo_descriptions);
       
       $seo_keywords = $author = new Zend_Form_Element_Text('seo_keywords', array(
            'required' => true,
            'label' => 'Ключевые слова страницы',
            'maxlength'   => '500',
            'validators'  => array(
                array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 500))
             ),
            'filters'     => array('StringTrim')
        ));
       
       $this->addElement($seo_keywords);
       
        
       
        
         
        
        // Кнопка Submit
        $submit = new Zend_Form_Element_Submit('submit', array(
            'label'       => 'Послать',
        ));
        
        $this->addElement($submit);

             
        
   
        
    }
}
