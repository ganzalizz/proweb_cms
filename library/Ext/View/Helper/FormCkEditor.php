<?php

require_once ROOT_DIR . '/js/ckeditor/ckeditor.php';
require_once ROOT_DIR . '/js/ckfinder/ckfinder.php';


// папка где лежит редактор


class Ext_View_Helper_FormCkEditor extends Zend_View_Helper_FormElement
{

    public function formCkEditor($name = null, $value = null, $attribs = null)
    {
        if (is_null($name) && is_null($value) && is_null($attribs)) {
            return $this;
        }
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        $editor = new CKEditor();        
        // пусть возвращает значение, а не выводит в браузер
        $editor->returnOutput = true;

        // задаем базовый путь к визуальному редактору
        $editor->basePath = '/js/ckeditor/';
       
        // ширина редактора
        $editor->config['width'] = 800;
        
        //$editor->config['filebrowserBrowseUrl'] = '/filemanager/index.html';
        
        $editor->config['filebrowserBrowseUrl'] = '/js/ckfinder/ckfinder.html';
        $editor->config['filebrowserImageBrowseUrl'] = '/js/ckfinder/ckfinder.html?type=Images';
        $editor->config['filebrowserFlashBrowseUrl'] = '/js/ckfinder/ckfinder.html?type=Flash';
        $editor->config['filebrowserUploadUrl'] = '/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
        $editor->config['filebrowserImageUploadUrl'] = '/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
        $editor->config['filebrowserFlashUploadUrl'] = '/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';

       




        // $value содержит значение по умолчанию
        return $editor->editor($name, $value);
    }

}
