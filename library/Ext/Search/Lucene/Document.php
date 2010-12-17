<?php

class Ext_Search_Lucene_Document extends Zend_Search_Lucene_Document {
	

	const TYPE_TEXT = 1 ;
	const TYPE_KEYWORD = 2 ;

	
	

	public function setUrl($value) {
		$this->myAddField( 'url', $value, self::TYPE_KEYWORD ) ;
	}

	public function setTitle( $value ) {
		$this->myAddField( 'title', $value ) ;
	}

	public function setContent($value) {
		$this->myAddField( 'content', $value ) ;
	}
	
	public function setType($value) {
		$this->myAddField( 'type', $value, self::TYPE_KEYWORD ) ;
	}
	public function setId($value) {
		$this->myAddField( 'id', $value, self::TYPE_KEYWORD ) ;
	}

	public function myAddField( $name, $value, $type = self::TYPE_TEXT ) {
		$types = array() ;
		if( $type & self::TYPE_TEXT ) {
			$types[] = array( "Zend_Search_Lucene_Field", "Text" ) ;
		}
		if( $type & self::TYPE_KEYWORD ) {
			$types[] = array( "Zend_Search_Lucene_Field", "Keyword" ) ;
		}
		foreach( $types as $funcname ) {
			$this->addField( call_user_func( $funcname, $name, $value, Ext_Search_Lucene::ENCODING ) ) ;
		}
	}

	public function getUrl() {
		$this->url ;
	}
	public function getTitle() {
		$this->title ;
	}
	public function getContent() {
		$this->content ;
	}
}