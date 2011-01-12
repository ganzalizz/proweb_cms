<?php

class Contacts_ContactsController extends Zend_Controller_Action 
{
     private $_page;

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
            $this->view->addHelperPath(Zend_Registry::get('helpersPaths'), 'View_Helper');

            $this->view->options = $options = PagesOptions::getInstance()->getPageOptions($id);
            $this->view->placeholder('title')->set($options->title);
            $this->view->placeholder('keywords')->set($options->keywords);
            $this->view->placeholder('descriptions')->set($options->descriptions);
            $this->view->placeholder('id_page')->set($id);        
            $this->view->placeholder('h1')->set($page->title);           
            $this->view->page = $page;
            $this->_page = $page;
        }
    }
    
    public function indexAction()
    {
        $form = new Form_FormContacts();
        $send_success = false;

        if ($this->_hasParam('template')) {
            $id = $this->_getParam('template');
            $template = Templates::getInstance()->getTemplateById($id);
            $this->layout->order_template = $template;
        }

        if ($this->_request->isPost())
        {
            if ($form->isValid($this->_request->getPost()))
            {                
                $mail = new Ext_Common_Mail();
                $mail->setMailBodyType('html');
                $subject = $message = 'Сообщение с сайта easystart.by. Страница - "'.$this->_page->title.'"';
		$message .= '<br/>Тема: '.$form->getValue('subject').'<br/><br/>';
                $message .= 'Email: '.$form->getValue('email').'<br/>';
                $message .= 'Имя: '.$form->getValue('name').'<br/>';
                $message .= '<br/>Сообщение: <br/>';
                $message .= $form->getValue('message').'<br/><br/>';

                if ($this->_hasParam('template')) {
                    $message .= 'Заказ шаблона: '.$template->title.'<br/>';
                    $message .= 'Стоимость: '.$template->price.'<br/>';
                    $message .= '<a href="http://easystart.by/templatesitem/item/'.$template->url.'">Перейти к шаблону на сайте</a>';
                }

                $ini = new Ext_Common_Config('contacts','frontend');
                $to = $ini->sendTo;

                $mail->SendMail($to,
                    $message,
                    $subject,
                    $form->getValue('name'));
                $send_success = true;
                
            } else {
                $this->view->errors = $form->renderFormErrors();
                $form->removeDecorator('FormErrors');
            }
        }
        
        $this->view->form = $form;
        $this->view->send_success = $send_success;
    }
    
    
}
