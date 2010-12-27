<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Form_FormPortfolio extends Ext_Form
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
        
        $this->setDecorators( array( array( 'ViewScript', array( 'viewScript' => 'admin/portfolio/form.phtml' ) ) ) );
        
        // Указываем метод формы
        $this->setMethod('post');
        
        
        
        $this->setAttrib('accept-charset', 'UTF-8');
        // Задаем атрибут class для формы
        $this->setAttrib('class', 'portfolio');
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
        
        $url_validator = new Zend_Validate_Db_NoRecordExists(array(
        	'table' => 'site_portfolio',
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

        $link = new Zend_Form_Element_Text('link', array(
            'required' => true,
            'label' => 'Ссылка на сайт (пример: http://easystart.by)',
            'maxlength'   => '100',
            'validators'  => array(
               // array('Alnum', true, array(true)),
                array('StringLength', true, array(3, 100))
             ),
            'filters'     => array('StringTrim')
        ));

        $this->addElement($link);
        

        $date_project = new ZendX_JQuery_Form_Element_DatePicker('date_project', array(
           'label' => 'Дата проекта',
           'required' => true
        ));
        $date_project->removeDecorator('label');
        $date_project->removeDecorator('htmlTag');

        $date_project->setJQueryParam('dateFormat', 'dd.mm.yy');

        $this->addElement($date_project);
               
        $content = new Ext_Form_Element_CkEditor('content', array(
                    'label' => 'Описание',
                    'required' => true,
                    'filters' => array('StringTrim')
                ));
        
        $this->addElement($content);
        
        $small_img = new Ext_Form_Element_Image('image');
       
        $small_img->addValidator('Count', false, 2);
        $small_img->addValidator('Extension', false, 'jpg,png,gif');
        $small_img->setLabel('Изображение');
        //$fields[] = $small_img;
        $this->addElement($small_img,'image');
       
       
        $is_active = new Zend_Form_Element_Checkbox('is_active', array(
			'label'		=> 'Публикация',
			'filters'	=> array('Int')
       ));     
       
       $this->addElement($is_active);

       $is_main = new Zend_Form_Element_Checkbox('is_main', array(
			'label'		=> 'На главную',
			'filters'	=> array('Int')
       ));

       $this->addElement($is_main);
              
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
             array('id', 'title', 'url', 'link', 'date_project', 'content', 'image'), 'templateDataGroup',
             array(
                     'legend' => 'Портфолио'
                    ));
       $this->addDisplayGroup(
             array('is_active', 'is_main'), 'publicDataGroup',
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
