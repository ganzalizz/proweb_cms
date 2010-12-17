<?php

class Ext_Resource_JsCssHeaders extends Zend_Application_Resource_ResourceAbstract
{
    
    
    private $_view = null;
    
    protected $_params = array ();
    
    protected $_options = array();
    
    /**
     * Set plugin options
     *
     * @param array $params
     */
    public function setParams(array $params) {
        $this->_params = $params;
    }
    
    /**
     * Return plugin options
     *
     * @return array
     */
    public function getParams() {
        return $this->_params;
    }
    
    
    
    
    public function  init() 
    {
      $this->_view = Zend_Layout::getMvcInstance()->getView();
      
      $this->_options = $this->getParams();
      
      
      $this->SetCssHeader();
      
      $this->SetJsHeader();
      
    }
    
    private function SetCssHeader()
    {
        if (isset($this->_options['css']))
        {
            $css_arr = explode(',', $this->_options['css']);
            
            
            foreach ($css_arr as $css)
            {    
              $this->_view->headLink()->appendStylesheet($css);
            }   
        
        }
        
    }
    
    private function SetJsHeader()
    {
       if (isset($this->_options['js']))
        {
            $js_arr = explode(',', $this->_options['js']);
            
            foreach ($js_arr as $js)
            {    
              $this->_view->headScript()->appendFile($js);
            }   
        
        } 
    }
}
