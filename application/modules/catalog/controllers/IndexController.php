<?php
class Catalog_IndexController extends Zend_Controller_Action {
	/**
	 * Шаблон страницы
	 * @var zend_layout
	 */
	private $layout = null;
	
	/**
	 * Язык страницы
	 * @var string
	 */
	private $lang = null;
	
	/**
	 * Объект страницы
	 * @var Pages_row
	 */
	private $_page = null;
	
	private $_onpage = 10;
	
	private $_current_page = null;
	
	private $_offset = null;
	
	public function init() {
		$this->view->addHelperPath( Zend_Registry::get( 'helpersPaths' ), 'View_Helper' );
		$id = $this->_getParam( 'id' );
		$page = Pages::getInstance()->getPage( $this->_getParam( 'id' ) );
		
		if (is_null( $page ) || $page->published == '0') {
			$this->_redirect( '/404' );
		}
		$this->_page = $page;
		$this->layout = $this->view->layout();
		$this->layout->setLayout( "front/catalog" );
		$this->layout->current_type = 'pages';
		$this->layout->lang = $this->lang;
		$this->layout->page = $this->_page;
		$this->lang = $this->_getParam( 'lang', 'ru' );
		if ($this->_hasParam( 'product' )) {
			$this->_forward( 'product' );
		}
		
		$this->_current_page = $this->view->current_page = $this->_getParam( 'page', 1 );
		$this->_offset = ($this->_current_page - 1) * $this->_onpage;
		$this->view->onpage = $this->_onpage;
		
		$this->view->content = $page->content;
		$this->view->options = $options = PagesOptions::getInstance()->getPageOptions( $id );
		$this->view->placeholder( 'title' )->set( $options->title );
		$this->view->placeholder( 'keywords' )->set( $options->keywords );
		$this->view->placeholder( 'descriptions' )->set( $options->descriptions );
		$this->view->placeholder( 'h1' )->set( $options->h1 );
		$this->view->placeholder( 'id_page' )->set( $id );
		
		$this->layout->id_object = $this->_page->id;
		
		if ($page->show_childs == 1) {
			$this->layout->page_childs = Pages::getInstance()->getChildrenAndURLs( $page->id );
		}
	}
	
	/**
	 * раздел каталога
	 */
	public function indexAction() {
		$id_division = $this->_getParam( 'division', 0 );
		$division = Catalog_Division::getInstance()->getItem( $id_division );
		if ($division != null) {
			$this->view->placeholder( 'title' )->set( $division->seo_title );
			$this->view->placeholder( 'keywords' )->set( $division->seo_keywords );
			$this->view->placeholder( 'descriptions' )->set( $division->seo_description );
			$this->view->placeholder( 'h1' )->set( $division->name );
			$this->view->layout()->bread_items = $division->getBread( $this->_page->path );
			
			if ($division->issetChilds()) {
				$this->view->childs = $division->getChilds( $this->_onpage, $this->_offset );
				$this->view->total = Catalog_Division::getInstance()->getCountOfChildren( $id_division );
			} elseif ($division->issetProductInDiv()) {
				$this->view->products = Catalog_Product::getInstance()->getPublicProductsByDivId( $id_division, $this->_onpage, $this->_offset );
				$this->view->total = Catalog_Product::getInstance()->getCountPublicInDiv( $id_division );
			
			}
			$this->view->division = $division;
			$this->layout->id_division = $division->id;
		
		} else {
			$this->view->childs = $childs = Catalog_Division::getInstance()->getRootItems( $this->_onpage, $this->_offset );			
			$this->view->total = Catalog_Division::getInstance()->getCountOfChildren( $id_division = 0 );
		}
		$this->view->id_division = $id_division;
	
	}
	
	/**
	 * раздел каталога
	 */
	public function productAction() {
		$id_product = $this->_getParam( 'product' );
		$product = Catalog_Product::getInstance()->getPublicItem( $id_product );
		$division = Catalog_Division::getInstance()->getItem( $product->id_division );
		if ($product != null) {
			$this->view->placeholder( 'title' )->set( $product->seo_title );
			$this->view->placeholder( 'keywords' )->set( $product->seo_keywords );
			$this->view->placeholder( 'descriptions' )->set( $product->seo_description );
			$this->view->placeholder( 'h1' )->set( $product->title );
			$bread_items = $division->getBread( $this->_page->path );
			$bread_items [] = array ('title' => $product->title );
			$this->view->layout()->bread_items = $bread_items;
		} else {
			$this->_redirect( '/404' );
		}
		
		$options_values = Catalog_Product_Options_Enabled::getInstance()->getAllOptionsValues( $id_product );
		$this->view->options = $options_values;
		$this->layout->id_product = $id_product;
		
		$this->view->product = $product;
		$this->layout->id_division = $product->id_division;
	
	}
}
