<?php

class Portfolio_PortfolioController extends Zend_Controller_Action {

    public function init() {
        $this->initView();
        $this->layout = $this->view->layout();
        $this->lang = $this->_getParam('lang', 'ru');

        $this->layout->setLayout("front/default");
        $id = $this->_getParam('id');
        $page = Pages::getInstance()->getPage($this->_getParam('id'));
        if(!is_null($page)) {
            if ($page->is_active == '0') {
                $this->_redirect('/404');
            }

            $this->layout->page = $page;
           // $this->layout->lang = $page->version;

            $this->view->addScriptPath(DIR_LAYOUTS) ;
            $this->view->addHelperPath(Zend_Registry::get('helpersPaths'), 'View_Helper') ;

            $this->view->options = $options = PagesOptions::getInstance()->getPageOptions($id);
            $this->view->placeholder('title')->set($options->title);
            $this->view->placeholder('keywords')->set($options->keywords);
            $this->view->placeholder('descriptions')->set($options->descriptions);
            $this->view->placeholder('id_page')->set($id);
            $this->view->placeholder('object_id')->set($id);
            $this->view->placeholder('h1')->set($page->title);
            $this->layout->current_type = 'pages';
            $this->view->page = $page;
            $this->layout->id_object = $page->id;
        }
    }

    public function indexAction() {
        if ($this->_hasParam('item')) $this->_forward('portfolioitem');
        $year = $this->_hasParam('year')? $this->_getParam('year'):'all';
        $id_page = $this->_getParam('id');
        
        $page =	Pages::getInstance()->getPageByParam('id', $this->_getParam('id'));
       // $this->view->child_pages = $child_pages = Pages::getInstance()->getPagesByParam('parentId', $this->_getParam('id'));
        $this->view->page = $page;
        $this->_setParam('id',$page->id);
        $this->view->year = $year;
        $this->view->years = Portfolio::getInstance()->getYearsForFilter();
        $this->view->portfolio = Portfolio::getInstance()->getActivePortfolio($year);
                
        
    }

    public function portfolioitemAction() {
        $portfolio_id = $this->_getParam('item', 0);
        $item = Portfolio::getInstance()->getPortfolioById($portfolio_id);
        if (isset($item) && $item) {
            $this->view->item =$portfolio_row = $item;
            if ($portfolio_row!=''){
            	$bread_items[] = array('title'=>$portfolio_row->title);
            	$this->view->placeholder('title')->set($portfolio_row->seo_title);
                $this->view->placeholder('keywords')->set($portfolio_row->seo_keywords);
                $this->view->placeholder('descriptions')->set($portfolio_row->seo_descriptions);
                $this->view->placeholder('h1')->set($portfolio_row->title);
            	$this->view->layout()->bread_items =  $bread_items;
            }
            $this->render('portfolioitem');
        }
    }   
}