<?php

class Forms_FormsController extends Zend_Controller_Action {

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
    public function feedbackAction() {
        Loader::loadCommon('Captcha');
        $captcha = new Captcha;
        $this->view->addHelperPath(Zend_Registry::get('helpersPaths') , 'View_Helper');
        if ($this->_request->isPost()) {
            $form = $this->_getParam('form', array());
            if ($form['fio'] == '' ||
                    $form['email'] == '' ||
                    $form['text'] == '' ||
                    $form['captcha'] == '') {
                $this->view->err = 1;
                $this->view->form = $form;
            } else if( !$captcha->isValidate($form['captcha'])) {
                $this->view->err = 2;
                $this->view->form = $form;
            } else {
                $users = Users::getInstance()->getUsersToSendMail();
                $emails = '';
                foreach ($users as $user) {
                    $emails[] = $user['email'];
                }
                $recipient = 'New message';
                $subject = 'Обратная связь';

                $template = FeedbackTemplates::getInstance()->getBySystemName('feedback');
                
                $subject = $template['name'];
                $template = $template['content'];

                foreach ($form as $key=>$param) {
                    $template = str_replace('{'.$key.'}', $param, $template);
                }
                $template = preg_replace('/{.+}/Usi', '', $template);
                $body = $template;
                Loader::loadCommon('Mail');
                $from = '';
                foreach($emails as $email){
                    Mail::send($email, $body, $from, $subject, $recipient);
                }
                $this->_request->clearParams();
                $this->view->ok = 1;
                $this->view->form = array();
            }
        }
        $this->view->captcha = $captcha->getOperation();
    }
}