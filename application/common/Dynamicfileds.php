<?php

/**
 * Класс, реализующий логику для вывода динамических полей
 *
 * Singleton class
 */
class Dynamicfileds
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

    public $_view = null;


    /**
     * Singleton instance
     * @return  Settings
     */
    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct() {
        $this->_view = Zend_Registry::get('view');
    }

 public function selectGenerate($name="someName", $defaults = null, $values) {
        $result = array();
        $options = '';

        $select = '<select class="width_315 group_item" name="'.$name.'[]">';

        foreach ($values as $item) {
            $prices_attrs = ' ';
            foreach ($item->_prices as $price) {
                $prices_attrs .= 'price_'.$price->id_size.'="'.$price->price.'"';
            }
            $selected = '';
            if (count($defaults)) {
                foreach ($defaults as $default) {
                    if ($item['id']==$default) $selected = 'selected="selected"';
                }
            }
            $select .= '<option '.$selected.' value="'.$item['id'].'" label="'.$item['title'].'"'.$prices_attrs.'>'.$item['title'].'</option>';
        }
        $select .= '</select>';

        $result[] = $select;
        return $result;
    }

    public function checkboxGenerate($name="someName", $defaults = null, $values) {
        $result = array();
        foreach ($values as $item) {
            $prices_attrs = ' ';
            foreach ($item->_prices as $price) {
                $prices_attrs.='price_'.$price->id_size.'="'.$price->price.'" ';
            }
            $selected = '';
            if (count($defaults)) {
                foreach ($defaults as $default) {
                    if ($item->id==$default) $selected = 'checked="checked"';
                }
            }
            $result[$item->title] = '<input '.$selected.' type="checkbox" value="'.$item->id.'" name="'.$name.'[]" '.$prices_attrs.' class="group_item">';
        }
        return $result;
    }

    public function radioGenerate($name="someName", $defaults = null, $values, $id) {
        $result = array();
        foreach ($values as $item) {
            $prices_attrs = ' ';
            //$prices_attrs = array();
            foreach ($item->_prices as $price) {
                $prices_attrs.='price_'.$price->id_size.'="'.$price->price.'" ';
                //$prices_attrs[$price->id_size]=$price->price;
            }
            //$prices_attrs['class']='group_item';
            $selected = '';
            if (count($defaults)) {
                foreach ($defaults as $default) {
                    if ($item->id==$default) $selected = 'checked="checked"';
                    //if ($item->id==$default) $prices_attrs['checked']='checked';
                }
            }
            //$result[$item->title] = $this->_view->formRadio($name."_radio['".$id."']", $item->id, $prices_attrs);
            $result[$item->title] = '<input '.$selected.' type="radio" value="'.$item->id.'" name="'.$name.'_radio['.$id.']" '.$prices_attrs.' class="group_item">';
        }
       
        return $result;
    }

    
}