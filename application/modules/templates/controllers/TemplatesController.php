<?php

class Templates_TemplatesController extends Zend_Controller_Action
{
    public function init() {
        $this->view->addScriptPath(DIR_LIBRARY.'Ext/View/Scripts/');
        $this->layout = $this->view->layout();
        $this->lang = $this->_getParam('lang', 'ru');

        $this->layout->setLayout("front/default");

        $id = $this->_getParam('id');
        $page = Pages::getInstance()->getPage($id);
        if(!is_null($page) && $page->is_active!=0) {
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

        //if ($this->_hasParam('item')) {
            //$this->_forward('item');
        //}
    }
    
    public function indexAction()
    {
        $ini = new Ext_Common_Config('templates','frontend');

        $page = $this->_getParam('page',1);
        $item_per_page = $ini->countOnPage;

        $paginator = Templates::getInstance()->getTemplatesPaginator($item_per_page, $page);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');

        $paginator->setView($this->view);
        $this->view->templates = $paginator->getCurrentItems();
        $this->view->paginator = $paginator;

    }

    public function templatesitemAction() {
        $template_url = $this->_getParam('item', '');
        $item = Templates::getInstance()->getTemplateByUrl($template_url);

        if (!is_null($item) && $item->is_active!=0) {
            Templates::getInstance()->addCountViews($item->id);
            $this->view->item = $template_row = $item;

            if ($template_row != ''){
            	$bread_items[] = array('title'=>$template_row->title);
            	$this->view->placeholder('title')->set($template_row->seo_title.' - '.$this->view->placeholder('title'));
                $this->view->placeholder('keywords')->set($template_row->seo_keywords);
                $this->view->placeholder('descriptions')->set($template_row->seo_descriptions);
                $this->view->placeholder('h1')->set($template_row->title);
            	$this->view->layout()->bread_items =  $bread_items;
            }
            
            $this->view->prev_item = Templates::getInstance()->getPrevTemplate($item->id);
            $this->view->next_item = Templates::getInstance()->getNextTemplate($item->id);

            $this->render('item');
        } else {
            $this->_redirect('/404');
        }
    }  
}
