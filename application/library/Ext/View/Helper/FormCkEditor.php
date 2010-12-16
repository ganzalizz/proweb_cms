<?php

require_once APPLICATION_PATH . '/../ckeditor/ckeditor.php';
require_once APPLICATION_PATH . '/../ckfinder/ckfinder.php';


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
        $editor->basePath = '/ckeditor/';
       
        // ширина редактора
        $editor->config['width'] = 800;
        
        //$editor->config['filebrowserBrowseUrl'] = '/filemanager/index.html';
        
        $editor->config['filebrowserBrowseUrl'] = '/ckfinder/ckfinder.html';
        $editor->config['filebrowserImageBrowseUrl'] = '/ckfinder/ckfinder.html?type=Images';
        $editor->config['filebrowserFlashBrowseUrl'] = '/ckfinder/ckfinder.html?type=Flash';
        $editor->config['filebrowserUploadUrl'] = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
        $editor->config['filebrowserImageUploadUrl'] = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
        $editor->config['filebrowserFlashUploadUrl'] = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';

       




        // $value содержит значение по умолчанию
        return $editor->editor($name, $value);
    }

}
