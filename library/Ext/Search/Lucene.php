<?php

class Ext_Search_Lucene extends Zend_Search_Lucene {
	
	const DIRECTORY = "search-index/";
	
	const PAGES = "pages";
	
	const NEWS = "news";
	
	const CATALOG_DIVISIONS = "catalog_divisions";
	
	const CATALOG_PRODUCTS = "catalog_products";
	
	const ENCODING = "utf-8";
	
	public function __construct($directory = null, $create = true) {
		Zend_Search_Lucene_Analysis_Analyzer::setDefault ( new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive ( ) );
		Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding ( self::ENCODING );
		parent::__construct ( ROOT_DIR . self::DIRECTORY . (is_null ( $directory ) ? "" : $directory), $create );
	}
	
	public static function open($directory) {
		try {
			$index = parent::open ( ROOT_DIR . self::DIRECTORY . $directory );
		} catch ( Exception $e ) {
			Zend_Search_Lucene_Analysis_Analyzer::setDefault ( new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive ( ) );
			Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding ( self::ENCODING );
			$index = self::create ( ROOT_DIR . self::DIRECTORY . $directory );
		
		}
		
		return $index;
	}
	
	/**
	 * удаление элемента из индекса
	 * @param int $id
	 * @param string $Indexname
	 */
	public static function deleteItemFromIndex($id, $IndexName) {
		$index = self::open ( $IndexName );
		$hits = $index->find ( 'id:' . $id );
		foreach ( $hits as $hit ) {
			$index->delete ( $hit->id );
		}
	}
}