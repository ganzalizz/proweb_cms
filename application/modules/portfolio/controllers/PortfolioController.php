<?php

class Portfolio_PortfolioController extends Zend_Controller_Action {

    public function init() {
        $this->initView();
        $this->layout = $this->view->layout();
        $this->lang = $this->_getParam('lang', 'ru');

        $this->layout->setLayout("front/default");

        $this->view->addScriptPath(DIR_LAYOUTS);
        $this->view->addHelperPath(Zend_Registry::get('helpersPaths'), 'View_Helper');
    }

    public function indexAction() {
        $id = $this->_getParam('id');
        $page = Pages::getInstance()->getPage($this->_getParam('id'));
        if (!is_null($page) && $page->is_active!=0) {
            $this->layout->page = $page;
            // $this->layout->lang = $page->version;

            $this->view->options = $options = PagesOptions::getInstance()->getPageOptions($id);
            $this->view->placeholder('title')->set($options->title);
            $this->view->placeholder('keywords')->set($options->keywords);
            $this->view->placeholder('descriptions')->set($options->descriptions);
            $this->view->placeholder('id_page')->set($id);
            $this->view->placeholder('object_id')->set($id);
            $this->view->placeholder('h1')->set($page->title);
            $this->layout->current_type = 'pages';
            $this->view->page = $page;
            $this->layout->id_page = $page->id;
        } else {
            $this->_redirect('/404');
        }

        $year = $this->_hasParam('year') ? $this->_getParam('year') : 'all';

        $this->view->page = $page;
        $this->_setParam('id', $page->id);
        $this->view->year = $year;
        $this->view->years = Portfolio::getInstance()->getYearsForFilter();
        $this->view->portfolio = Portfolio::getInstance()->getActivePortfolio($year);
        $this->view->portfolio_count = Portfolio::getInstance()->getPortfolioCount();
    }

    public function portfolioitemAction() {
        $portfolio_url = $this->_getParam('item', '');
        $item = Portfolio::getInstance()->getPortfolioByUrl($portfolio_url);

        if (isset($item) && $item) {
            $this->view->item = $portfolio_row = $item;
            if ($portfolio_row != '') {
                $bread_items[] = array('title' => $portfolio_row->title);
                $this->view->placeholder('title')->set($portfolio_row->seo_title);
                $this->view->placeholder('keywords')->set($portfolio_row->seo_keywords);
                $this->view->placeholder('descriptions')->set($portfolio_row->seo_descriptions);
                $this->view->placeholder('h1')->set($portfolio_row->title);
                $this->view->layout()->bread_items = $bread_items;
            }

            $this->view->prev_item = Portfolio::getInstance()->getPrevPortfolio($item->id);
            $this->view->next_item = Portfolio::getInstance()->getNextPortfolio($item->id);

            $this->render('portfolioitem');
        }
    }

}