<?php

class ProductsForm extends Ext_Form
{

    public function init()
    {
        $id = new Zend_Form_Element_Hidden('id', array('label'=>'titles[id]'));
        $this->addElement($id);
        
        
        $id_division = new Zend_Form_Element_Text('id_division', array('label'=>'titles[id_division]'));
        $this->addElement($id_division);
        
        
        $priority = new Zend_Form_Element_Text('priority', array('label'=>'titles[priority]'));
        $this->addElement($priority);
        
        
        $active = new Zend_Form_Element_Checkbox('active', array('label'=>'titles[active]'));
        $this->addElement($active);
        
        
        $title = new Zend_Form_Element_Text('title', array('label'=>'titles[title]'));
        $this->addElement($title);
        
        
        $price = new Zend_Form_Element_Text('price', array('label'=>'titles[price]'));
        $this->addElement($price);
        
        
        $intro = new Ext_Form_Element_CkEditor('intro', array('label'=>'titles[intro]'));
        $this->addElement($intro);
        
        
        $description = new Ext_Form_Element_CkEditor('description', array('label'=>'titles[description]'));
        $this->addElement($description);
        
        
        $is_new = new Zend_Form_Element_Text('is_new', array('label'=>'titles[is_new]'));
        $this->addElement($is_new);
        
        
        $popular = new Zend_Form_Element_Text('popular', array('label'=>'titles[popular]'));
        $this->addElement($popular);
        
        
        $seo_title = new Zend_Form_Element_Text('seo_title', array('label'=>'titles[seo_title]'));
        $this->addElement($seo_title);
        
        
        $seo_keywords = new Zend_Form_Element_Textarea('seo_keywords', array('label'=>'titles[seo_keywords]'));
        $this->addElement($seo_keywords);
        
        
        $seo_description = new Zend_Form_Element_Textarea('seo_description', array('label'=>'titles[seo_description]'));
        $this->addElement($seo_description);
    }


}

