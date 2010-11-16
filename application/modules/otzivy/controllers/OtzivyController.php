<?php

class Otzivy_OtzivyController extends Zend_Controller_Action {

    public function init() {
        $this->initView();
        $this->layout = $this->view->layout();
        $this->lang = $this->_getParam('lang', 'ru');

        $this->layout->setLayout("front/default");
        $id = $this->_getParam('id');
        $page = Pages::getInstance()->getPage($this->_getParam('id'));
        if(!is_null($page)) {
            if ($page->published == '0') {
                $this->_redirect('/404');
            }

            $this->layout->page = $page;
            $this->layout->lang = $page->version;

            $this->view->addScriptPath(DIR_LAYOUTS) ;
            $this->view->addHelperPath(Zend_Registry::get('helpersPaths'), 'View_Helper') ;

            $this->view->options = $options = PagesOptions::getInstance()->getPageOptions($id);
            $this->view->placeholder('title')->set($options->title);
            $this->view->placeholder('keywords')->set($options->keywords);
            $this->view->placeholder('descriptions')->set($options->descriptions);
            $this->view->placeholder('id_page')->set($id);
            $this->view->placeholder('object_id')->set($id);
            $this->view->placeholder('h1')->set($page->name);
            $this->layout->current_type = 'pages';
            $this->view->page = $page;
            $this->layout->id_object = $page->id;
        }
    }

    public function indexAction() {
        
        $form = new Form_FormOtzivy();
              
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_getAllParams())){
                Otzivy::getInstance()->addOtziv($form->getValues());
                $form = new Form_FormOtzivy();
            }
            
        }    
        $id_page = $this->_getParam('id');
        $page =	Pages::getInstance()->getPageByParam('id', $this->_getParam('id'));
        $this->view->child_pages = $child_pages = Pages::getInstance()->getPagesByParam('parentId', $this->_getParam('id'));
        $this->view->page = $page;
        $this->_setParam('id',$page->id);
        $this->view->otzivy = Otzivy::getInstance()->getActiveOtzivy();
        
        $this->view->form = $form;
        
        //$this->view->addHelperPath(Zend_Registry::get('helpersPaths') , 'View_Helper');
        
        //$this->view->captcha = $captcha->getOperation();
                
        
    }

    public function articlesitemAction() {
        $article_id = $this->_getParam('item', 0);
        $item = Articles::getInstance()->getArticleById($article_id);
        if (isset($item) && $item) {
            $this->view->item =$articles_row = $item;
            if ($articles_row!=''){
            	$bread_items[] = array('title'=>$articles_row->name);
            	$this->view->placeholder('title')->set($articles_row->seo_title);
                $this->view->placeholder('keywords')->set($articles_row->seo_keywords);
                $this->view->placeholder('descriptions')->set($articles_row->seo_descriptions);
                $this->view->placeholder('h1')->set($articles_row->name);
            	$this->view->layout()->bread_items =  $bread_items;
            }
            $this->render('articlesitem');
        }
    }   
}