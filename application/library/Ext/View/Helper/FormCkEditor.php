<?php

require_once APPLICATION_PATH . '/../ckeditor/ckeditor.php';

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

        // $value содержит значение по умолчанию
        return $editor->editor($name, $value);
    }

}
