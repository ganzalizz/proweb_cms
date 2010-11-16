<?php

class Ext_View_Helper_FormFck extends Zend_View_Helper_FormElement {

	public $width = "90%" ;
	public $height = "300px" ;
	public $type = 'Default' ;

	public function formFck( $name, $value = null, $attribs = null, $options = null, $listsep = null ) {
		$info = $this->_getInfo( $name, $value, $attribs ) ;
		extract( $info ) ; // id, name, value, attribs, options, listsep, disable

		if( empty( $attribs['width'] ) ) {
			$attribs['width'] =  $this->width ;
		}
		if( empty( $attribs['height'] ) ) {
			$attribs['height'] = (int)$this->height ;
		}
		if( empty( $attribs['type'] ) ) {
			$attribs['type'] = $this->type ;
		}

		//@todo: fix constants
		$oFCKeditor = new Ext_Common_FCKeditor( $name ) ;
		$oFCKeditor->BasePath = '/fckeditor/' ;
		$oFCKeditor->Value = $value ;
		$oFCKeditor->Width = $attribs['width'] ;
		$oFCKeditor->Height = $attribs['height'] ;
		$oFCKeditor->ToolbarSet = $attribs['type'] ;
		$oFCKeditor->Config['SkinPath'] = $oFCKeditor->BasePath . 'editor/skins/silver/' ;
		if ($disable) {
			$oFCKeditor->Config["disable"] = "disable" ;
		}

		$layout = Zend_Layout::getMvcInstance() ;
		$layout->id = $id ;
		$layout->label = 'test' ;
		//$layout->width = Data::readArray( $attribs, "width", "100%" ) ;
		$layout->html = $oFCKeditor->CreateHtml() ;

		return $layout->render( "admin/form/form-fck" ) ;
	}
}