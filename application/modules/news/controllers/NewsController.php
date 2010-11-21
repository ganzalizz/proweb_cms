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
            if ($page->is_active == '0') {
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
            $this->view->placeholder('h1')->set($page->title);
           
           // $this->view->page = $page;
           // $this->layout->id_object = $page->id;
        }
    }

    public function indexAction() {
      
       if ($this->_hasParam('item')) $this->_forward('newsitem'); 
        
       $ini = new Ext_Common_Config('news','frontend');
       $registry = $ini->getModuleConfigSection();
       if ($registry instanceof Zend_Registry) 
       $conf = $registry->get('frontend');
       
       $page = $this->_getParam('page',1);
       $item_per_page = $conf->news->per->page;
       
       $offset = $page ? (($page - 1) * $item_per_page):0;
       
       $this->view->news = News::getInstance()->getAllActivePage($item_per_page, $offset);
       
       
       $this->view->addHelperPath('Ext/View/Helper', 'Ext_View_Helper');
       $this->view->pagination_config = array( 'total_items'=>100,
                                               'items_per_page'=>25,
                                               'style'=>'extended');
       //$this->view->paginator = News::getInstance()->getPaginatorRows($page);
      // echo $this->view->getScriptPaths(); 
        
        $router = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRoute();
        //$router = Zend_Controller_Front::getInstance()->getRouter()
        
        //print_r($router);

       // var_dump(  Zend_Controller_Front::getInstance()->getBaseUrl());
        
       
        
         
       $this->view->news = News::getInstance()->getAllActive();
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