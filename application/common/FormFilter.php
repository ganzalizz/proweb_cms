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
    

}