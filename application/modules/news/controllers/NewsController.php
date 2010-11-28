<?php

class News_NewsController extends Zend_Controller_Action {

    private $_session;
    
    public function preDispatch() {
        Ext_Form_Element_Uploadify::bypassSession();
        $this->_session = new Zend_Session_Namespace('uplodify');
    }
    
    
    public function init() {
        
       
       	$this->view->addScriptPath(DIR_LIBRARY.'Ext/View/Scripts/');       
        $this->layout = $this->view->layout();
        $this->lang = $this->_getParam('lang', 'ru');
        
        
        $this->layout->setLayout("front/default");
        $id = $this->_getParam('id');
        $page = Pages::getInstance()->getPage($this->_getParam('id'));
        if(!is_null($page)) {
            if ($page->is_active == '0') {
                $this->_redirect('/404');
            }
            
            $this->view->options = $options = PagesOptions::getInstance()->getPageOptions($id);
            $this->view->placeholder('title')->set($options->title);
            $this->view->placeholder('keywords')->set($options->keywords);
            $this->view->placeholder('descriptions')->set($options->descriptions);
            $this->view->placeholder('id_page')->set($id);
            $this->view->placeholder('object_id')->set($id);
            $this->view->placeholder('h1')->set($page->title);
           
        }
    }

    public function indexAction() {
        
       $ini = new Ext_Common_Config('news','frontend');
       $registry = $ini->getModuleConfigSection();      
       $conf = $registry->get('frontend');  
            
       $page = $this->_getParam('page',1);
       $item_per_page = $conf->news->per->page;
            
       $paginator = News::getInstance()->getNewsPaginator($item_per_page,$page);
       Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
      
       $paginator->setView($this->view);
       $this->view->news =  $paginator->getCurrentItems();
       $this->view->paginator = $paginator;
      
    }

    public function newsitemAction() {
        $new_id = $this->_getParam('item', 0);
        $item = News::getInstance()->getNewsById($new_id);
        
        if (isset($item) && $item) {
            News::getInstance()->addCountViews($new_id);
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