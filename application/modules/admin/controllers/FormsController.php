<?php

/**
 * FormsController
 * 
 * @author
 * @version 
 */



class Admin_FormsController extends MainAdminController {
	
	
	private $_element_types = array(
		'Zend_Form_Element_Hidden'		=> 'Hidden',
		'Zend_Form_Element_Text'		=> 'Text',
		'Zend_Form_Element_Textarea'	=> 'Textarea',
		'Ext_Form_Element_CkEditor'		=> 'CkEditor',
		'Zend_Form_Element_Checkbox'	=> 'Checkbox',
		'Zend_Form_Element_Select'		=> 'Select',
		
		
	);
	
	
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		
		$table_name = $this->_getParam('table_name');
		if ($this->_request->isPost()){
			if ($table_name){
				FormModel::getInstance()->setTableName($table_name);
				$table_metadata = FormModel::getInstance()->getMetadata(); 
				foreach ($table_metadata as $name=>$field){
					$element_type = FormModel::getInstance()->checkElementType($field);
					$table_metadata[$name]['ELEMENT_TYPE'] = $element_type; 
				}
				
			}
			if ($this->_getParam('metadata')){
				$titles = $this->_getParam('titles');
				$types = $this->_getParam('types');
				foreach ($table_metadata as $name => $field){					
					$table_metadata[$name]['TITLE'] = $titles[$name];					
					$table_metadata[$name]['ELEMENT_TYPE'] = $types[$name];
				}
				FormModel::getInstance()->createFormClass($this->_getParam('class_name'), $table_metadata);
			}
			$this->view->metadata = $table_metadata;
			$this->view->element_types = $this->_element_types;
			$this->view->table_name = $table_name;
			$this->view->class_name = $this->_getParam('class_name');
		}
		$this->view->all_tables = FormModel::getInstance()->getAllTables();
		
		
			
	}

}
