<?php

class Articles_ArticlesController extends Zend_Controller_Action {

    public function init() {
        
        $this->view->addScriptPath(DIR_LIBRARY.'Ext/View/Scripts/');
        //$this->initView();
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
            //$this->view->placeholder('h1')->set($page->name);
            $this->layout->current_type = 'pages';
            $this->view->page = $page;
            $this->layout->id_page = $page->id;
        }
    }

    public function indexAction() {
         

//        $id_page = $this->_getParam('id');
//        $page =	Pages::getInstance()->getPageByParam('id', $this->_getParam('id'));
//        $this->view->child_pages = $child_pages = Pages::getInstance()->getPagesByParam('parentId', $this->_getParam('id'));
//        $this->view->page = $page;
//        $this->_setParam('id',$page->id);
        
        $ini = new Ext_Common_Config('articles','frontend');
       
            
       $page = $this->_getParam('page',1);
       $item_per_page = $ini->per->page;
            
       $paginator = Articles::getInstance()->getArticlesPaginator($item_per_page,$page);
       Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
      
       $paginator->setView($this->view);
       $this->view->articles =  $paginator->getCurrentItems();
       $this->view->paginator = $paginator;
       
                
        
    }

    public function articlesitemAction() {
        $article_id = $this->_getParam('item', 0);
        $item = Articles::getInstance()->getArticleById($article_id);
        if (isset($item) && $item) {
            Articles::getInstance()->addCountViews($article_id);
            $this->view->item =$articles_row = $item;
            
            if ($articles_row!=''){
            	$bread_items[] = array('title'=>$articles_row->name);
            	$this->view->placeholder('title')->set($articles_row->seo_title);
                $this->view->placeholder('keywords')->set($articles_row->seo_keywords);
                $this->view->placeholder('descriptions')->set($articles_row->seo_descriptions);
                //$this->view->placeholder('h1')->set($articles_row->name);
            	$this->view->layout()->bread_items =  $bread_items;
            }
            $this->render('articlesitem');
        }
    }   
}