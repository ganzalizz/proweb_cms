<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Ext_View_Helper_ImageUpload extends Zend_View_Helper_FormFile 
{
   
 public function imageUpload($name, $value, $attribs, $options) {
    $str = parent::formFile($name, $attribs = null);
    
    if(!empty($options[$name])) {
      $str .= $this->getImagePreview($name, $options[$name] );
    } else {
      $str .= $this->getEmptyPreview();
    }
    
    return $str;
  }
//$options[$name]
  private function getImagePreview($name, $path) {
    $view = Zend_Layout::getMvcInstance()->getView();  
    $img = ($view->doctype()->isXhtml())
       ? '<img src="'.$path.'" alt="'.$name.'" />'
       : '<img src="'.$path.'" alt="'.$name.'">';
    
    return '<p class="preview">'.$img.'</p>';
  }
  
  private function getEmptyPreview() {
    return '<p class="preview">No image uploaded.</p>';
  }
}
