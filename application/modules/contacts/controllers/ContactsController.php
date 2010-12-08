<?php

class Contacts_ContactsController extends Zend_Controller_Action 
{
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
    
    public function indexAction()
    {
        $form = new Form_FormOtzivy();
        
        if ()
        {
            if ($form->isValid($form->getValues()))
            $mail = new Ext_Common_Mail();
            $mail->setMailBodyType('text');
            $mail->SendMail($email_to,
                            $form->getValue('message'),
                            $form->getValue('subject'), 
                            $form->getValue('name'));
        }
        
        $this->view->form = $form;
    }
}
