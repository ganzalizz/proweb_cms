<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Form_PagesForm extends Ext_Form
{
	
	private static $_idRecord = null;
	
	private static $_div_type_value = null;
	private static $_div_typeOptions = array();
	
	private static $_menu_values = array();
	private static $_menuOptions = array();
	
	/**
     * установка id записи в базе 
     * для проверки уникальность поля url
     * @param $id
     */
    public static function setRecordId($id){
    	return self::$_idRecord = $id;
    }
    
	/**
     * 
     * @param int $options
     */
    public static function setdiv_typeValue($value){
    	return self::$_div_type_value = $value;
    }
    
	/**
     * 
     * @param array $options
     */
    public static function setdiv_typeOptions($options){
    	return self::$_div_typeOptions = $options;
    }
	/**
     * 
     * @param array $values
     */
    public static function setMenuValues($values){
    	return self::$_menu_values = $values;
    }
    
	/**
     * 
     * @param array $options
     */
    public static function setMenuOptions($options){
    	return self::$_menuOptions = $options;
    }
	
	
    public function init()
    {
        parent::init();
        
        //$this->setName('news');
        $this->setAction('');
        
        $this->setDecorators( array( array( 'ViewScript', array( 'viewScript' => 'admin/pages/form.phtml' ) ) ) );
        
        // Указываем метод формы
        $this->setMethod('post');
        
        
        
        $this->setAttrib('accept-charset', 'UTF-8');
        // Задаем атрибут class для формы
        $this->setAttrib('class', 'news');
        $this->setAttrib('enctype', 'multipart/form-data');
        
        
        $id = new Zend_Form_Element_Hidden('id');
        $this->addElement($id);
        
        $id_parent = new Zend_Form_Element_Hidden('id_parent');
        $id_parent->addFilter('Int');
        $this->addElement($id_parent);
        
        
        $title = new Zend_Form_Element_Text('title', array(
			'required'		=> true,
			'label'			=> 'Название',
			'description'	=> 'Используется в меню, от 5 до 150 символов',
            'maxlength'		=> '150',
            'validators'	=> array(               
                array('StringLength', true, array(5, 150))
             ),             
            'filters'     => array('StringTrim')
        ));
        
        $this->addElement($title);
        
        $h1 = new Zend_Form_Element_Text('h1', array(
			'required'		=> true,
			'label'			=> 'Заголовок страницы',
			'description'	=> 'от 5 до 150 символов',
            'maxlength'		=> '150',
            'validators'	=> array(               
                array('StringLength', true, array(5, 150))
             ),             
            'filters'     => array('StringTrim')
        ));
        
        $this->addElement($h1);
        
        $path = new Zend_Form_Element_Text('path', array(
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
        	'table' => 'site_content',
        	'field' => 'path'
    	));
    	if (self::$_idRecord!=null){
    		$url_validator->setExclude(array(
				'field' => 'id',
	            'value' => self::$_idRecord
	
	        ));
    	}
        $path->addValidator($url_validator);
        
        $this->addElement($path);
        
       
         $intro = new Ext_Form_Element_CkEditor('intro', array(
                    'label' => 'Анонс',
         			'description' => 'не более 1000 символов',
                    'required' => true,         			
                    'filters' => array('StringTrim')
                ));
        
        $this->addElement($intro);
        
        $content = new Ext_Form_Element_CkEditor('content', array(
                    'label' => 'Текст',
                    'required' => true,
                    'filters' => array('StringTrim')
                ));
        
        $this->addElement($content);
        
       
       
        $small_img = new Ext_Form_Element_Image('img');
       
        $small_img->addValidator('Count', false, 1);
        $small_img->addValidator('Extension', false, 'jpg,png,gif');
        $small_img->setLabel('Фотография');
        //$fields[] = $small_img;
        $this->addElement($small_img,'img');
       
       
        
        $id_div_type = new Zend_Form_Element_Select('id_div_type', array('label' => 'Тип раздела'));
        $id_div_type->setMultiOptions(self::$_div_typeOptions);
        $id_div_type->setValue(self::$_div_type_value);
        $id_div_type->addFilter('Int');
        
        $this->addElement($id_div_type);
        
       
		$is_active = new Zend_Form_Element_Checkbox('is_active', array(
			'label'		=> 'Страница активна',       				
			'filters'	=> array('Int')
       ));     
       
       $this->addElement($is_active);
       
	   $show_childs = new Zend_Form_Element_Checkbox('show_childs', array(
			'label'		=> 'Отображать вложенные',       				
			'filters'	=> array('Int')
       ));     
       
       $this->addElement($show_childs);
       
       $show_in_sitemap = new Zend_Form_Element_Checkbox('show_in_sitemap', array(
			'label'		=> 'Отображать в карте сайта',       				
			'filters'	=> array('Int')
       ));     
       
       $this->addElement($show_in_sitemap);
       
       
       
       $page_title = $author = new Zend_Form_Element_Text('page_title', array(
            'required' => true,
            'label' => 'Заголовок страницы',
            'maxlength'   => '150',
            'validators'  => array(                
                array('StringLength', true, array(5, 150))
             ),
            'filters'     => array('StringTrim')
        ));
       
       $this->addElement($page_title);
       
       $descriptions = new Zend_Form_Element_Text('descriptions', array(
            'required' => true,
            'label' => 'Описание страницы',
            'maxlength'   => '300',
       		'description' => 'от 5  до 300 символов',
            'validators'  => array(
              //  array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 300))
             ),
            'filters'     => array('StringTrim')
        ));
       
       $this->addElement($descriptions);
       
       $keywords =  new Zend_Form_Element_Text('keywords', array(
            'required' => true,
            'label' => 'Ключевые слова страницы',
       		'description' => 'от 5  до 500 символов',
            'maxlength'   => '500',
            'validators'  => array(
               // array('Alnum', true, array(true)),
                array('StringLength', true, array(5, 500))
             ),
            'filters'     => array('StringTrim')
        ));
       
       $this->addElement($keywords);
       
       $menu = new Zend_Form_Element_MultiCheckbox('menu', array('label' => 'Принадлежность к меню'));
       $menu->addMultiOptions(self::$_menuOptions);
       $menu->setValue(self::$_menu_values);
       
       $this->addElement($menu);
       
       $this->addDisplayGroup(
             array('id', 'id_parent', 'title', 'h1', 'path', 'intro', 'content','img'), 
             'pageDataGroup',
             array('legend' => 'Страница')
       );
                    
       $this->addDisplayGroup(
             array('id_div_type','is_active', 'show_childs', 'show_in_sitemap'), 
             'publicDataGroup',
             array('legend' => 'Правила публикации')
       );
                    
        $this->addDisplayGroup(
             array('menu'), 
             'manuDataGroup',
             array('legend' => 'Меню')
        );             
       
		$this->addDisplayGroup(
             array('page_title', 'descriptions', 'keywords'), 
             'seocDataGroup',
             array('legend' => 'SEO')
        );
       
        
       
        
        
        
        $submit = new Zend_Form_Element_Button('submit','<span><em>Сохранить</em></span>');
        $submit->setAttrib('escape', false);
        $submit->setAttrib('type', 'submit');        
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

