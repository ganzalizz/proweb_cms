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
            if ($page->is_active == '0') {
                $this->_redirect('/404');
            }

            $this->layout->page = $page;
            $this->layout->id_page = $page->id;
                       

            $this->view->addScriptPath(DIR_LAYOUTS) ;
            $this->view->addScriptPath(DIR_LIBRARY.'Ext/View/Scripts/'); 
            $this->view->addHelperPath(Zend_Registry::get('helpersPaths'), 'View_Helper') ;

            $this->view->options = $options = PagesOptions::getInstance()->getPageOptions($id);
            $this->view->placeholder('title')->set($options->title);
            $this->view->placeholder('keywords')->set($options->keywords);
            $this->view->placeholder('descriptions')->set($options->descriptions);
            $this->view->placeholder('id_page')->set($id);        
            $this->view->placeholder('h1')->set($page->title);           
            $this->view->page = $page;            
        }
    }

    public function indexAction() {
        
        $form = new Form_FormOtzivy(); 
              
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_getAllParams())){
                Otzivy::getInstance()->addOtziv($form->getValues());
                $mail = new Ext_Common_Mail();
                $mail->setMailBodyType('text');
                $mail->SendMail('avenger999@gmail.by',
                            $form->getValue('content'),
                            $form->getValue('Новое сообщение в книге отзывов и предложений'), 
                            $form->getValue('email'));
                $form = new Form_FormOtzivy();
                $this->view->ok = 1;
                
            }
        }    
       $ini = new Ext_Common_Config('otzivy','frontend');
            
       $page = $this->_getParam('page',1);
       $item_per_page = $ini->onpage;       
       Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
       $prizn = $this->_getParam('prizn', null);      
	       
       $paginator = Otzivy::getInstance()->getActiveOtzivy($prizn, $page, $item_per_page);
       $paginator->setView($this->view);
       $this->view->prizn = $prizn;
       $this->view->otzivy =  $paginator->getCurrentItems();
       $this->view->paginator = $paginator;        
       $this->view->form = $form;
       if (!is_null($prizn)){
			$this->view->url_params = array('prizn'=>$prizn);
			
       } 
       
        
    }

    
}