<?php

class News_NewsController extends Zend_Controller_Action {

    public function init() {
        
        print_r($this->_getAllParams());
       $this->initView();
       echo var_dump($this->view->getScriptPaths());
        $this->layout = $this->view->layout();
        $this->lang = $this->_getParam('lang', 'ru');

        
        $this->layout->setLayout("front/default");
        $id = $this->_getParam('id');
        $page = Pages::getInstance()->getPage($this->_getParam('id'));
        if(!is_null($page)) {
            if ($page->published == '0') {
                $this->_redirect('/404');
            }

           // $this->layout->page = $page;
           // $this->layout->lang = $page->version;

           // $this->view->addScriptPath(DIR_LAYOUTS) ;
          //  $this->view->addHelperPath(Zend_Registry::get('helpersPaths'), 'View_Helper') ;

            $this->view->options = $options = PagesOptions::getInstance()->getPageOptions($id);
            $this->view->placeholder('title')->set($options->title);
            $this->view->placeholder('keywords')->set($options->keywords);
            $this->view->placeholder('descriptions')->set($options->descriptions);
            $this->view->placeholder('id_page')->set($id);
            $this->view->placeholder('object_id')->set($id);
            $this->view->placeholder('h1')->set($page->name);
           
           // $this->view->page = $page;
           // $this->layout->id_object = $page->id;
        }
    }

    public function indexAction() {
      
      // if ($this->_hasParam('item')) $this->_forward('newsitem'); 
        
       $ini = new Ext_Common_Config('news','frontend');
       $registry = $ini->getModuleConfigSection();
       if ($registry instanceof Zend_Registry) 
       $conf = $registry->get('frontend');
       
       $page = $this->_getParam('page',1);
       $item_per_page = $conf->news->per->page;
       
       
       $paginator = News::getInstance()->getNewsPaginator($item_per_page,$page);
       Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
       $paginator->setView(Zend_Layout::getMvcInstance()->getView());
       $this->view->news =  $paginator->getCurrentItems();
       $this->view->paginator = $paginator;
       print_r($paginator->);
       
        

        
        
       
        
         
      
    }

    public function newsitemAction() {
        $new_id = $this->_getParam('item', 0);
        $item = News::getInstance()->getNewsById($new_id);
        if (isset($item) && $item) {
            $this->view->item =$news_row = $item;
            if ($news_row!=''){
            	$bread_items[] = array('title'=>$news_row->name);
            	$this->view->placeholder('title')->set($news_row->seo_title);
                $this->view->placeholder('keywords')->set($news_row->seo_keywords);
                $this->view->placeholder('descriptions')->set($news_row->seo_descriptions);
                $this->view->placeholder('h1')->set($news_row->name);
            	$this->view->layout()->bread_items =  $bread_items;
            }
            $this->render('newsitem');
        }
    }   
}