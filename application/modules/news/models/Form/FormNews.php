<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Form_FormNews extends Ext_Form
{
    const DIR_TMP = '/uploads/';
    const MAX_FILE_SIZE = 5120000;
    
    private static $_idRecord = null;
    
    
    /**
     * установка id записи в базе 
     * для проверки уникальность поля url
     * @param $id
     */
    public static function setRecordId($id){
    	return self::$_idRecord = $id;
    }
    
     public function init()
    {
        parent::init();
        
        //$this->setName('news');
        $this->setAction('');
        
        $this->setDecorators( array( array( 'ViewScript', array( 'viewScript' => 'admin/news/form.phtml' ) ) ) );
        
        // Указываем метод формы
        $this->setMethod('post');
        
        
        
        $this->setAttrib('accept-charset', 'UTF-8');
        // Задаем атрибут class для формы
        $this->setAttrib('class', 'news');
        $this->setAttrib('enctype', 'multipart/form-data');
        
        
        $id = new Zend_Form_Element_Hidden('id');
        
        $this->addElement($id);
        
        $name = new Zend_Form_Element_Text('name', array(
			'required'		=> true,
			'label'			=> 'Название',
			'description'	=> 'от 5 до 150 символов',
            'maxlength'		=> '150',
            'validators'	=> array(
                array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 150))
             ),             
            'filters'     => array('StringTrim')
        ));
        
        $this->addElement($name);
        
        $url = new Zend_Form_Element_Text('url', array(
			'required'		=> true,
			'label'			=> 'Url',
			'description'	=> 'Только латинские символы',
            'maxlength'		=> '150',
            'validators'	=> array(
                //array('Alnum', true, array(true)),               
				array('Regex', false, array('/^[a-z0-9_-]{1,}$/'))
             ),             
            'filters'     => array('StringTrim')
        ));
        
        $url_validator = new Zend_Validate_Db_NoRecordExists(array(
        	'table' => 'site_news',
        	'field' => 'url'
    	));
    	if (self::$_idRecord!=null){
    		$url_validator->setExclude(array(
				'field' => 'id',
	            'value' => self::$_idRecord
	
	        ));
    	}
        $url->addValidator($url_validator);
        
        $this->addElement($url);
        
       
         $teaser = new Ext_Form_Element_CkEditor('teaser', array(
                    'label' => 'Анонс',
         			'description' => 'не более 1000 символов',
                    'required' => true,         			
                    'filters' => array('StringTrim')
                ));
        
        $this->addElement($teaser);
        
        $content = new Ext_Form_Element_CkEditor('content', array(
                    'label' => 'Текст новости',
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
       $date_news->removeDecorator('label');
       $date_news->removeDecorator('htmlTag');
       
       
       $date_news->setJQueryParam('dateFormat', 'dd.mm.yy');
       
       $this->addElement($date_news);
        
        $author = new Zend_Form_Element_Text('author', array(
            'required' => true,
            'label' => 'Автор новости',
            'maxlength'   => '150',
            'validators'  => array(
                //array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 150))
             ),
            'filters'     => array('StringTrim')
        ));
        
       $this->addElement($author);
       
       $created_at = new ZendX_JQuery_Form_Element_DatePicker('created_at', array(
           'label' => 'Дата публикации',
           
       ));
       $created_at->removeDecorator('label');
       $created_at->removeDecorator('htmlTag');
       
       $created_at->setJQueryParam('dateFormat', 'dd.mm.yy');
       
       $this->addElement($created_at);
       
       
        $small_img = new Ext_Form_Element_Image('small_img');
       
        $small_img->addValidator('Count', false, 2);
        $small_img->addValidator('Extension', false, 'jpg,png,gif');
        $small_img->setLabel('Маленькая фотография');
        //$fields[] = $small_img;
        $this->addElement($small_img,'small_img');
        
        $big_img = new Ext_Form_Element_Image('big_img');
       
        $big_img->addValidator('Count', false, 2);
        $big_img->addValidator('Extension', false, 'jpg,png,gif');
        $big_img->setLabel('Большая фотография');
        $big_img->setAttrib('is_big', true);
       // $fields[] = $big_img;
        $this->addElement($big_img,'big_img');

       
       
      
       
       //TODO: Date element
       
		$is_active = new Zend_Form_Element_Checkbox('is_active', array(
			'label'		=> 'Новость активна',       				
			'filters'	=> array('Int')
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
                //array('Alnum', true, array(true)),
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
              //  array('Alnum', true, array(true)),
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
               // array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 500))
             ),
            'filters'     => array('StringTrim')
        ));
       
       $this->addElement($seo_keywords);
       
       $this->addDisplayGroup(
             array('id', 'name', 'url', 'teaser', 'content','link','date_news','author','created_at','small_img', 'big_img'), 'newsDataGroup',
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
       
        
       
        
        
        
        $submit = new Zend_Form_Element_Button('submit','<span><em>Сохранить</em></span>');
        $submit->setAttrib('escape', false);
        $submit->setAttrib('type', 'submit');
        //$submit->setAttrib('content', '<span><em>Сохранить</em></span>');
        $this->addElement($submit);

             
        foreach ($this->getElements() as $element){
        	
        	if (!strpos($element->getType(), 'JQuery')){        	
	        	$element->setDecorators(array(
	        		array( 'ViewHelper' ),	
	        		array( 'Errors' )
	        	));
        	}
        }
        
        
        
        
        
   
        
    }
}
