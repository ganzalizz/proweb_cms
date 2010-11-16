<?php
class Search_IndexController extends Zend_Controller_Action {
	/**
	 *
	 * @var zend_layout
	 */
	private $layout = null;
	
	/**
	 *
	 * @var string
	 */
	private $lang = null;
	
	private $_count = null;
	
	private $_offset = null;
	
	private $_current_page = null;
	
	public function init() {
		/*$this->initView()
		$view = Zend_Layout::getMvcInstance()->getView();*/
		
		$this->view->addHelperPath( Zend_Registry::get( 'helpersPaths' ), 'View_Helper' );
		$this->layout = $this->view->layout();
		$this->lang = $this->_getParam( 'lang', 'ru' );
		$this->layout->lang = $this->lang;
		
		if ($this->_hasParam( 'reindex' )) {
			$this->_forward( 'reindex' );
		}
		$id = $this->_getParam( 'id' );
		$this->view->placeholder( 'id_page' )->set( $id );
		$page = Pages::getInstance()->find( $id )->current();
		
		if (! is_null( $page )) {
			if ($page->published == 0) {
				$this->_redirect( '/404' );
			}
			$options = PagesOptions::getInstance()->getPageOptions( $id );
			$this->view->placeholder( 'title' )->set( $options->title );
			$this->view->placeholder( 'keywords' )->set( $options->keywords );
			$this->view->placeholder( 'descriptions' )->set( $options->descriptions );
			$this->view->placeholder( 'h1' )->set( $options->h1 );
			$this->layout->page = $page;
			$this->view->current_page = $this->_current_page = $this->_getParam( 'page', 1 );
			$this->view->onpage = $this->_count = 20;
		}
	}
	
	public function reindexAction() {
            $numDocs = News::getInstance()->reindex();
            $numDocs += Pages::getInstance()->reindex();
            $numDocs += Catalog_Division::getInstance()->reindex();
            $numDocs += Catalog_Product::getInstance()->reindex();
            echo $numDocs;
            exit();
	}
	
	public function indexAction() {
		$this->layout->setLayout( "front/default" );
		$hits = array ();
		$search = $this->_getParam( 'keywords' );
		if ($search) {
			
			$charset = $this->detect_cyr_charset( $search );		
			if ('w' == $charset || 'm' == $charset) {
				$search = mb_convert_encoding( $search, 'utf-8', 'windows-1251' );
			}
			$this->view->placeholder( 'title' )->set( $this->view->placeholder( 'title' ) . ": $search" );
			$this->view->placeholder( 'keywords' )->set( $this->view->placeholder( 'keywords' ) . ": $search" );
			$this->view->placeholder( 'descriptions' )->set( $this->view->placeholder( 'descriptions' ) . ": $search" );
			
			$this->view->layout()->search = $search;
			$search = Zend_Search_Lucene_Search_QueryParser::parse( strip_tags( $search ), Ext_Search_Lucene::ENCODING );
			
			/**
			 * 
			 * поиск по товарам
			 */
			
			$result = Ext_Search_Lucene::open( Ext_Search_Lucene::CATALOG_PRODUCTS );
			$result = $result->find( $search );
			$hits = array_merge( $hits, $result );
			
			/**
			 * поиск по разделам каталога
			 */	
			$result = Ext_Search_Lucene::open( Ext_Search_Lucene::CATALOG_DIVISIONS );
			$result = $result->find( $search );
			$hits = array_merge( $hits, $result );
			
			// поиск по новостям			
			$news_index = Ext_Search_Lucene::open( Ext_Search_Lucene::NEWS);
			$result = $news_index->find( $search );			
			$hits = array_merge( $hits, $result );
			
			// поиск по страницам
			$result =  Ext_Search_Lucene::open( Ext_Search_Lucene::PAGES );
			$result = $result->find( $search );
			$hits = array_merge( $hits, $result );
			
			
			
			
			if (! sizeof( $hits )) {
				$this->view->err = $message = "найдено результатов 0";
				//echo $message;
			} else {
				$this->view->total = count( $hits );
				$count = $this->_count;
				$offset = ($this->_current_page - 1) * $this->_count;
				$hits = array_slice( $hits, $offset, $count );
				$this->view->hits = $hits;
			}
		}
	
	}
	
	private function detect_cyr_charset($str) {
		define( 'LOWERCASE', 3 );
		define( 'UPPERCASE', 1 );
		$charsets = Array ('k' => 0, 'w' => 0, 'd' => 0, 'i' => 0, 'm' => 0 );
		for($i = 0, $length = strlen( $str ); $i < $length; $i ++) {
			$char = ord( $str [$i] );
			//non-russian characters
			if ($char < 128 || $char > 256)
				continue;
				
			//CP866
			if (($char > 159 && $char < 176) || ($char > 223 && $char < 242))
				$charsets ['d'] += LOWERCASE;
			if (($char > 127 && $char < 160))
				$charsets ['d'] += UPPERCASE;
				
			//KOI8-R
			if (($char > 191 && $char < 223))
				$charsets ['k'] += LOWERCASE;
			if (($char > 222 && $char < 256))
				$charsets ['k'] += UPPERCASE;
				
			//WIN-1251
			if ($char > 223 && $char < 256)
				$charsets ['w'] += LOWERCASE;
			if ($char > 191 && $char < 224)
				$charsets ['w'] += UPPERCASE;
				
			//MAC
			if ($char > 221 && $char < 255)
				$charsets ['m'] += LOWERCASE;
			if ($char > 127 && $char < 160)
				$charsets ['m'] += UPPERCASE;
				
			//ISO-8859-5
			if ($char > 207 && $char < 240)
				$charsets ['i'] += LOWERCASE;
			if ($char > 175 && $char < 208)
				$charsets ['i'] += UPPERCASE;
		
		}
		arsort( $charsets );
		return key( $charsets );
	}
}
