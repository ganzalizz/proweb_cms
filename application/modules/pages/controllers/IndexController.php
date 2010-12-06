<?php
class pages_IndexController extends Zend_Controller_Action {
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
    private  $_page = null;

    
    public function init() { 
    	    	
        $id = $this->_getParam('id');
        $page = Pages::getInstance()->getPage($this->_getParam('id'));
        
        if(is_null($page) || $page->is_active == '0') {
            $this->_redirect('/404');
        }        
        
        $this->lang = $this->_getParam('lang', 'ru');
        $this->_page = $page;
        if($this->_page->path =='404'){
			header("HTTP/1.0 404 Not Found");
		}
        $this->view->content = $page->content;
        $this->view->options = $options = PagesOptions::getInstance()->getPageOptions($id);
        $this->view->placeholder('title')->set($options->title);
        $this->view->placeholder('keywords')->set($options->keywords);
        $this->view->placeholder('descriptions')->set($options->descriptions);
        $this->view->placeholder('h1')->set($options->h1);
        $this->view->placeholder('id_page')->set($id);

        $this->layout = $this->view->layout();
        $this->layout->setLayout("front/default");
        $this->layout->current_type = 'pages';
        $this->layout->lang = $this->lang;
        $this->layout->page = $this->_page;
       
        
        
    }
    
    public function indexAction()
    {
       
    }
    
    /**
     * Открытие страницы с переданным id
     */
    public function pageAction() {
    	 /*$page = $this->_page->toArray();
    	 unset($page['id']);
    	 for ($index = 1; $index <= 200; $index++) {
    	 	$page['title'] = 'test_page_'.$index;
    	 	Pages::getInstance()->insert($page);
    	 }*/
    	//set_time_limit(0);
    	//Pages::getInstance()->reindex();
    	//require_once DIR_LIBRARY.'Phpmorphy/examples/example-0.3.x.php';
    	
    	//Search_Index::getInstance()->procesText($this->_page->content);
    	//Search_Index::getInstance()->buildIndexes();
    	//Search_Index::getInstance()->search('pages', 'значительное событие');
    	
        
    }

    /**
     *  Открытие главной страницы
     */
    public function mainAction() {
        $this->layout->setLayout("front/main");
        
    }

    /**
     * Карта сайта
     */
    public function sitemapAction() {
        $this->view->sitemap = Pages::getInstance()->getSitemap($this->lang);

    }

    /**
     * Обратная связь
     */
    public function feedbackAction() {
        $this->layout->setLayout("front/one_col");
        $this->view->form = $this->_getParam('form', array());

    }
}
