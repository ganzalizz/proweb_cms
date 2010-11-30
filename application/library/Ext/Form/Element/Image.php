<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Ext_Form_Element_Image extends Zend_Form_Element 
{
  public $helper = "imageUpload";
  public $options;
  
  public function __construct($image_name, $attributes, $data_item) {
    $this->options = $data_item;
    parent::__construct($image_name, $attributes);
  }
}

