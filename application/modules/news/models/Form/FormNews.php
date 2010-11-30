<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Form_FormNews extends Ext_Form
{
    const DIR_TMP = '/uploads/';
    const MAX_FILE_SIZE = 5120000;
    
     public function init()
    {
        parent::init();
        
        //$this->setName('news');
        $this->setAction('');
        // Указываем метод формы
        $this->setMethod('post');
        
        
        
        $this->setAttrib('accept-charset', 'UTF-8');
        // Задаем атрибут class для формы
        $this->setAttrib('class', 'news');
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $id = new Zend_Form_Element_Hidden('id');
        
        $this->addElement($id);
        
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
                //array('Alnum', true, array(true)),
                array('StringLength', true, array(11, 255))
             ),
            'filters'     => array('StringTrim')
        ));
        
        $link->addValidator(new Ext_Form_Validate_UrlValidator());
        
        $this->addElement($link);
        
        $date_news = new ZendX_JQuery_Form_Element_DatePicker('date_news', array(
           'label' => 'Дата новости',
           
       ));
       
       $date_news->setJQueryParam('dateFormat', 'dd.mm.yy');
       
       $this->addElement($date_news);
        
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
       
       $created_at = new ZendX_JQuery_Form_Element_DatePicker('created_at', array(
           'label' => 'Дата публикации',
           
       ));
       
       $created_at->setJQueryParam('dateFormat', 'dd.mm.yy');
       
       $this->addElement($created_at);
       
       
//        $small_img = new Ext_Form_Element_File('small_img');
//        $small_img->setOptions(array(
//            'required' => true, 
//            'label' => 'Upload file:'
//        ))->setDestination(realpath(APPLICATION_PATH . self::DIR_TMP))->addValidators(array(
//            array('Count', 
//                true, 
//                1
//            ), 
//            array('Extension', 
//                true, 
//                array(
//                    'csv',
//                    'txt',
//					'jpg',
//					'png',
//					'gif'
//                )
//            ), 
//            array('Size', 
//                true, 
//                self::MAX_FILE_SIZE
//            )
//            /*array('MimeType', 
//                true, 
//                array(
//                    'text/anytext', 
//                    'text/comma-separated-values', 
//                    'text/csv', 
//                    'text/plain', 
//                    'application/csv', 
//                    'application/excel', 
//                    'application/msexcel', 
//                    'application/vnd.ms-excel', 
//                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
//                    'application/x-excel', 
//                    'application/x-msexcel', 
//                    'application/x-ms-excel', 
//                    'application/xls', 
//                    'application/xlt', 
//                    'application/octet-stream'
//                )
//            )*/
//        ))->addPrefixPath('Ext_Form_Decorator', 'Ext/Form/Decorator/', 'decorator')->setDecorators(array(
//            'File', 
//            'Description', 
//            'Label', 
//            array('Uploadify', 
//                array(
//                    'text' => 'Закачать'
//                )
//            ), 
//            array('Errors', 
//                array(
//                    'placement' => 'prepend'
//                )
//            )
//        ))->create();
       // $this->addElement($file);
       
       
//        $small_img = new Ext_Form_Element_File( 'small_img' );
//        $small_img->setOptions(array(
//            'required' => false,
//            'label' => 'Картинка'
//        ))
//        ->setDestination( '/tmp' )
//        ->addValidators(array(
//            array( 'Count', true, 1 )
//           
//        ))
//        ->addPrefixPath('Ext_Form_Decorator', 'Ext/Form/Decorator/', 'decorator')
//        ->setDecorators(array(
//            'File',
//            'Description',
//            'Label',
//            array('Errors', array('placement' => 'prepend')),
//            array('Uploadify', array('text' => 'Загрузить'))
//        ))
//        ->create();
 //       $this->addElement($small_img);
       
       
       // $options['small_img'] = '/pics/news/small_img_thumb/test.jpg'; 
        $small_img = new Ext_Form_Element_Image('small_img', null, $options);
       
        $small_img->addValidator('Count', false, 1);
        $small_img->addValidator('Extension', false, 'jpg,png,gif');
        $small_img->setLabel('Маленькая фотография');
        $fields[] = $small_img;
        $this->addElement($small_img,'small_img');

       
       
       
//       $small_img = new Ext_Form_Element_File('small_img', 
//       array('label' => 'Картинка')); 
////       $small_img->setLabel('Маленькое изображение');
//       //$small_img->addValidator('Count', false, 1);
//       //$small_img->addValidator('Extension', false, 'jpg,png,gif');
//       //$small_img->setLabel('My Image');
//       //$fields[] = $small_img;
//       $this->addElement($small_img);
       
       
       //$big_img = new Ext_Form_Element_FileUpload('big_img', null, $options);
      // $this->addElement($big_img);
       
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
       
       $seo_descriptions = new Zend_Form_Element_Text('seo_descriptions', array(
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
       
       $this->addDisplayGroup(
             array('name', 'teaser', 'content','link','date_news','author','created_at','small_img'), 'newsDataGroup',
             array(
                     'legend' => 'Новость'
                    ));
       $this->addDisplayGroup(
             array('is_active', 'is_main', 'is_hot'), 'publicDataGroup',
             array(
                     'legend' => 'Правила публикации'
                    ));
       
       $this->addDisplayGroup(
             array('seo_title', 'seo_descriptions', 'seo_keywords'), 'seocDataGroup',
             array(
                     'legend' => 'SEO'
                    ));
       
        
         
        
        // Кнопка Submit
        $submit = new Zend_Form_Element_Submit('submit', array(
            'label'       => 'Послать',
        ));
        
        $this->addElement($submit);

             
        
   
        
    }
}
