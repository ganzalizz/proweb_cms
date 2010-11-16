<?php
define('COUNT', 50);

class Admin_IndexController extends MainAdminController {
    /**
     * Аутентификация
     *
     */
    public function loginAction() {
        $this->_helper->layout->disableLayout();


        if(Security::getInstance()->checkAdminAllow()) {
            $this->_redirect('/admin/');	//отправляем на управление содержимым
        }
        elseif ($this->_request->isPost()) {
            $username = $this -> getRequest()->getParam('login');
            $password = $this -> getRequest()->getParam('pwd');

            if(!$this->view->error = Security::getInstance()->checkAdminLogin($username, $password)) {
                $this->_redirect('/admin/');	//отправляем на управление содержимым
            }
        }
    }
    
    public function indexAction() {
        echo 'ok';
    }
}
