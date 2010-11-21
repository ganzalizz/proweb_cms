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
      //  echo 'fufelok';
//        //exit ();

    	$view = Zend_Layout::getMvcInstance()->getView();
    	print_r($view->getScriptPaths());
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
       // $this->layout->id_object = $this->_page->id;

        /*if ($page->show_childs==1) {
            $this->layout->page_childs = Pages::getInstance()->getChildrenAndURLs($page->id);
        }*/
        
        
    }
    
    public function indexAction()
    {
        echo 'ok';
        $this->view->fufelok = "<p>Fufelok</p>";
    }
    
    /**
     * Открытие страницы с переданным id
     */
    public function pageAction() {
        
    }

    /**
     *  Открытие главной страницы
     */
    public function mainAction() {
        $this->layout->setLayout("front/main");
        
        //print_r($this->view->getScriptPaths());
        $this->initView();
        
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
