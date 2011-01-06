<?php

class News_NewsController extends Zend_Controller_Action {

    public function init() {

        $this->view->addScriptPath(DIR_LIBRARY . 'Ext/View/Scripts/');
        $this->layout = $this->view->layout();
        $this->lang = $this->_getParam('lang', 'ru');


        $this->layout->setLayout("front/default");
    }

    public function indexAction() {
        $id = $this->_getParam('id');
        $page = Pages::getInstance()->getPage($id);
        if (!is_null($page) && $page->is_active != 0) {
            $this->view->page = $page;
            $this->view->options = $options = PagesOptions::getInstance()->getPageOptions($id);
            $this->view->placeholder('title')->set($options->title);
            $this->view->placeholder('keywords')->set($options->keywords);
            $this->view->placeholder('descriptions')->set($options->descriptions);
            $this->view->placeholder('id_page')->set($id);
            $this->view->placeholder('object_id')->set($id);
            $this->view->placeholder('h1')->set($page->title);
            $this->layout->id_page = $page->id;
            $this->layout->page = $page;
        } else {
            $this->_redirect('/404');
        }

        $ini = new Ext_Common_Config('news', 'frontend');


        $page = $this->_getParam('page', 1);
        $item_per_page = $ini->countOnPage;

        $paginator = News::getInstance()->getNewsPaginator($item_per_page, $page);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');

        $paginator->setView($this->view);
        $this->view->news = $paginator->getCurrentItems();
        $this->view->paginator = $paginator;
    }

    public function newsitemAction() {
        $new_url = $this->_getParam('item', '');
        $item = News::getInstance()->getItemByUrl($new_url);

        if (!is_null($item)) {
            News::getInstance()->addCountViews($item->id);
            $this->view->item = $news_row = $item;

            if ($news_row != '') {
                $bread_items[] = array('title' => $news_row->name);
                $this->view->placeholder('title')->set($news_row->seo_title);
                $this->view->placeholder('keywords')->set($news_row->seo_keywords);
                $this->view->placeholder('descriptions')->set($news_row->seo_descriptions);
                $this->view->placeholder('h1')->set($news_row->name);
                $this->view->layout()->bread_items = $bread_items;
            }
            $this->render('newsitem');
        }
    }

}