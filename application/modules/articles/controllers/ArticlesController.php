<?php

class Articles_ArticlesController extends Zend_Controller_Action {

    public function init() {

        $this->view->addScriptPath(DIR_LIBRARY . 'Ext/View/Scripts/');
        //$this->initView();
        $this->layout = $this->view->layout();
        $this->lang = $this->_getParam('lang', 'ru');

        $this->view->addScriptPath(DIR_LAYOUTS);
        $this->view->addHelperPath(Zend_Registry::get('helpersPaths'), 'View_Helper');

        $this->layout->setLayout("front/default");
    }

    public function indexAction() {

        $id = $this->_getParam('id');
        $page = Pages::getInstance()->getPage($this->_getParam('id'));
        if (!is_null($page) && $page->is_active!=0) {
            $this->layout->page = $page;

            $this->view->options = $options = PagesOptions::getInstance()->getPageOptions($id);
            $this->view->placeholder('title')->set($options->title);
            $this->view->placeholder('keywords')->set($options->keywords);
            $this->view->placeholder('descriptions')->set($options->descriptions);
            $this->view->placeholder('id_page')->set($id);
            $this->view->placeholder('object_id')->set($id);
            //$this->view->placeholder('h1')->set($page->name);
            $this->layout->current_type = 'pages';
            $this->view->page = $page;
            $this->layout->id_page = $page->id;
        } else {
            $this->_redirect('/404');
        }

        $ini = new Ext_Common_Config('articles', 'frontend');

        $page = $this->_getParam('page', 1);
        $item_per_page = $ini->countOnPage;

        $paginator = Articles::getInstance()->getActiveArticles($item_per_page, $page);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');

        $paginator->setView($this->view);
        $this->view->articles = $paginator->getCurrentItems();
        $this->view->paginator = $paginator;
    }

    public function articlesitemAction() {
        $article_url = $this->_getParam('item', '');
        $item = Articles::getInstance()->getItemByUrl($article_url);
        if (isset($item) && $item) {
            Articles::getInstance()->addCountViews($item->id);
            $this->view->item = $articles_row = $item;

            if ($articles_row != '') {
                $bread_items[] = array('title' => $articles_row->name);
                $this->view->placeholder('title')->set($articles_row->seo_title);
                $this->view->placeholder('keywords')->set($articles_row->seo_keywords);
                $this->view->placeholder('descriptions')->set($articles_row->seo_descriptions);
                //$this->view->placeholder('h1')->set($articles_row->name);
                $this->view->layout()->bread_items = $bread_items;
            }
            $this->render('articlesitem');
        }
    }

}