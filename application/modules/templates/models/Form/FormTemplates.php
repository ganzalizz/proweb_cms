<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Form_FormTemplates extends Ext_Form
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
        
        $this->setDecorators( array( array( 'ViewScript', array( 'viewScript' => 'admin/templates/form.phtml' ) ) ) );
        
        // Указываем метод формы
        $this->setMethod('post');
        
        
        
        $this->setAttrib('accept-charset', 'UTF-8');
        // Задаем атрибут class для формы
        $this->setAttrib('class', 'templates');
        $this->setAttrib('enctype', 'multipart/form-data');
        
        
        $id = new Zend_Form_Element_Hidden('id');
        
        $this->addElement($id);
        
        $name = new Zend_Form_Element_Text('title', array(
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

        // Автотранслит для url
        $url_filter = new Zend_Filter_Callback(
            array(
                'callback' => array('Ext_Common_Translit', 'transliterate'),
            )
        );
        $url->addFilter($url_filter);
        
        $url_validator = new Zend_Validate_Db_NoRecordExists(array(
        	'table' => 'site_templates',
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

        $price = new Zend_Form_Element_Text('price', array(
			'required'		=> true,
			'label'			=> 'Стоимость',
			'description'	=> '',
            'maxlength'		=> '150',
            'validators'	=> array(
                array('Alnum', true, array(true)),
                array('StringLength', true, array(1, 100))
             ),
            'filters'     => array('StringTrim')
        ));

        $this->addElement($price);
               
        $content = new Ext_Form_Element_CkEditor('describe_template', array(
                    'label' => 'Описание',
                    'required' => true,
                    'filters' => array('StringTrim')
                ));
        
        $this->addElement($content);
        
        $small_img = new Ext_Form_Element_Image('template_image');
       
        $small_img->addValidator('Count', false, 2);
        $small_img->addValidator('Extension', false, 'jpg,png,gif');
        $small_img->setLabel('Изображение');
        //$fields[] = $small_img;
        $this->addElement($small_img,'template_image');  
       
       
        $is_active = new Zend_Form_Element_Checkbox('is_active', array(
			'label'		=> 'Шаблон активен',
			'filters'	=> array('Int')
       ));     
       
       $this->addElement($is_active);
              
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
             array('id', 'title', 'url', 'price', 'describe_template', 'template_image'), 'templateDataGroup',
             array(
                     'legend' => 'Шаблон'
                    ));
       $this->addDisplayGroup(
             array('is_active'), 'publicDataGroup',
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
