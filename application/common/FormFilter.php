<?php

/**
 * FormFilter
 *
 * Singleton class
 */
class FormFilter
{
    /**
     * Singleton instance member
     * @var Settings
     */
    protected static $_instance = null;
    
    /**
     * Zend_Config_Ini instance
     * @var Zend_Config_Ini
     */
    public $_config = null;
	
	
    /**
     * Singleton instance
     * @return  FormFilter
     */
    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    /**
     * 
     * @param array $validators
     * @param array $params
     * @return Zend_Filter_Input
     * 
     */
    public function getFilterInput(array $validators, array $params = null) {
        $filter = new Zend_Filter_Input(array(),$validators, $params);
        $filter->setDefaultEscapeFilter(new Zend_Filter_HtmlEntities(array('charset'=> 'utf-8')));        
        $options = Configurator::getConfig('messages')->input->toArray();
        $filter->setOptions($options);
       
        return $filter;
    }
    
//    private function setErrorMessages(){
//    	
//    	$filter_prefix = "Zend_Validate_";
//    	$messages = Configurator::getConfig('messages')->toArray();
//    	if (isset($messages) && count($messages)){
//    		foreach ($messages as $cur_filter=>$mess){
//    			if (is_array($mess)){
//    				$filter = $filter_prefix.$cur_filter;
//    				$obj_filter = new $filter();   
//    				//print_r($mess); 				
//    				$obj_filter->setMessages($mess);
//    				//print_r($obj_filter->getMessages());
//    				//exit;
//    				
//    			}
//    		}
//    	}
//    	
//    	
//    }
}