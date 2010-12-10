<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Ext_View_Helper_ImageUpload extends Zend_View_Helper_FormFile {
	
	private $_is_big = false;
	
	public function imageUpload($name, $value, $attribs) {
		$str = parent::formFile( $name, null );
		
		if (isset($attribs['is_big']) && $attribs['is_big']){
			$this->_is_big = (bool)$attribs['is_big'];
		}
		
		if (! empty( $attribs [$name] )) {
			$str .= $this->getImagePreview( $name, $attribs [$name] );
		} else {
			$str .= $this->getEmptyPreview();
		}
		
		
		
		return $str;
	}
	
	private function getImagePreview($name, $path) {
		$view = Zend_Layout::getMvcInstance()->getView();
		if ($this->_is_big ===false){
			$img = ($view->doctype()->isXhtml()) ? '<img src="' . $path . '" alt="' . $name . '" />' : '<img src="' . $path . '" alt="' . $name . '">';
		} else {
			$img = '<a href="'.$path.'" title="'.$name.'" target="_blank">Посмотреть фото</a>';
		}
		
		$delete = '<input type="checkbox" value="1" name="' . $name . '_delete" id="' . $name . '_delete">' . '<label for="' . $name . '_delete"> Удалить</label>';
		
		return '<p class="preview">' . $img . '<br/>' . $delete . '</p>';
	}
	
	private function getEmptyPreview() {
		return '<p class="preview">No image uploaded.</p>';
	}
}
