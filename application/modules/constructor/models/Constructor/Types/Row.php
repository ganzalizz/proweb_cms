<?php

class Constructor_Types_Row extends Zend_Db_Table_Row {
    public $_groups = array();
    public $_sizes = array();


    public function getSizesFields($name="someName", $default = null) {
        if (!$this->_sizes) return null;
        $result = '';
        $options = array();
        foreach ($this->_sizes as $size) {
            $selected = '';
            if ($default && $size->id==$default) {
                $selected = 'checked="checked"';
            }
            $key = $size->price?" (".$size->price." бр.)":'';
            $result[$size->title.$key] = '<input '.$selected.' class="size group_item" type="radio" price_'.$size->id.'="'.$size->price.'"  value='.$size->id.' name="sizes" />';
        }
        return $result;
    }
}

