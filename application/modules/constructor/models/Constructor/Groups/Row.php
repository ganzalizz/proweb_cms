<?php

class Constructor_Groups_Row extends Zend_Db_Table_Row {
    protected $_view = null;
    public $_values = array();


    /**
     * Получение полей формы
     * @param name of input $name
     * @param value by default $defaults
     * @return string html-code
     */
    public function getFormField($name, $defaults = null) {
        Loader::loadCommon('Dynamicfileds');
        $dynamicFileds = DynamicFileds::getInstance();
        
        $result = array();
        switch ($this->form_type) {
            case 'select': $result = $dynamicFileds->selectGenerate($name, $defaults, $this->_values); break;
            case 'radio': $result = $dynamicFileds->radioGenerate($name, $defaults, $this->_values, $this->id); break;
            case 'checkbox': $result = $dynamicFileds->checkboxGenerate($name, $defaults, $this->_values); break;
        }
        return $result;
    }
}