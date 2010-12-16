<?php

/**
 * FormModel
 * 
 * @author Vitali
 * @version 
 */



class FormModel extends Zend_Db_Table {
	/**
	 * The default table name 
	 */
	protected $_name = 'form';
	
	
	protected $_sequence = false;
    protected static $_instance = null;
	
	/**
     * Singleton instance
     *
     * @return FormModel
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    /**
     * 
     * установка имени таблицы
     * @param string $name
     */
    public function setTableName($name){
    	$this->_name = $name;    	
    	$this->_setupMetadata();
    	
    }
    /**
     * описание таблицы в виде массива
     * @return array metadata
     */
    public function getMetadata(){
    	return $this->_metadata;
    }
	
	public function checkElementType($field) {
		$element_prefix = 'Zend_Form_Element_';
		
		if ($field ['PRIMARY'] == 1) {
			$element = $element_prefix . 'Hidden';
		} else {
			switch ($field ['DATA_TYPE']) {
				case 'int' :
				case 'smallint' :
				case 'varchar' :
				case 'float' :
					$element = $element_prefix . 'Text';
					;
					break;
				
				case 'tinyint' :
					$element = $element_prefix . 'Checkbox';
					break;
				
				case 'text' :
				case 'tinytext' :
					$element = $element_prefix . 'Textarea';
					break;
				default :
					$element = $element_prefix . 'Text';
					break;
			}
		
		}
		return $element;
	
	}
	
	public function createFormClass($class_name,  $metadata){
		$form = new Zend_CodeGenerator_Php_Class(); 
    	$form->setName($class_name);
    	$form->setExtendedClass('Ext_Form');
    	
    	$init_method = new Zend_CodeGenerator_Php_Method();
    	$init_method->setName('init');
    	$init_method->setBody($this->createBody($metadata));
    	
    	$form->setMethod($init_method);
    	
    	
		$file = new Zend_CodeGenerator_Php_File();
		$file->setClass($form);
		//echo $file->generate();	
		$file_name = $form->getName().'.php'; 	
		file_put_contents($file_name, $file->generate());
	}
    
    public function createBody($metadata){
    	
    	$element_prefix = 'Zend_Form_Element_';
    	//Zend_Form_Element_Checkbox::::
    	$body = '';
    	foreach ($metadata as $name=> $field){
    		
    		$body.= '$'.$name.' = new '.$field['ELEMENT_TYPE']."('$name', array('label'=>'".$field['TITLE']."'));\n".
    				'$this->addElement($'.$name.');'. "\n\n\n";
    	}
    	return $body;
    }	
    
    /**
     * плучение списка таблиц в виде массива
     * @return array
     */
    public function getAllTables(){
    	$tables = array();
    	$db_config = $this->_db->getConfig();
    	$sql = 'SHOW FULL TABLES FROM `'.$db_config['dbname'].'` WHERE table_type = \'BASE TABLE\'';
    	
    	$result = $this->_db->fetchCol($sql, 'Tables_in_'.$db_config['dbname']);    	
    	foreach ($result as $table){
    		$tables[$table] = $table;
    	}
    	return $tables;
    	
    }
    
    

}
