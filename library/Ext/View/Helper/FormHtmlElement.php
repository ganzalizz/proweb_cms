<?php

class Ext_View_Helper_FormHtmlElement extends Zend_View_Helper_Abstract {



	public function formHtmlElement($type,  $name, $value = null, $attribs = null, $options = null, $listsep = null ) {
		
		$layout = Zend_Layout::getMvcInstance();
		$layout->name = $name;
		$layout->value = $value;
		$layout->attribs = $attribs;
		$layout->options = $options;
		
		$types = Catalog_Product_Default::getInstance()->getTypes();
		if (array_key_exists($type, $types) ){
			switch ($type) {
				case 'input':return $layout->render( "admin/form/form-text" ) 
				;
				break;
				case 'textarea':return $layout->render( "admin/form/form-textarea" ) 
				;
				break;
				case 'checkbox':return $layout->render( "admin/form/form-checkbox" ) 
				;
				break;
				case 'fck':return $layout->render( "admin/form/form-getfck" ) 
				;
				break;
				case 'fck_small':						
						$attribs['type']= 'Basic';
						$attribs['height']= '150';
						$layout->attribs = $attribs;				
						
						return $layout->render( "admin/form/form-getfck" ) 
				;
				break;
				
				default: return
					;
				break;
			}
		}
		

		return '' ;
	}
}