<?php
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
        elseif(Security::getInstance()->checkManagerAllow()) {
            $this->_redirect('/orders/ru/admin_orders/');	//отправляем на управление содержимым
        }
        elseif ($this->_request->isPost()) {

            $username = $this -> getRequest()->getParam('login');
            $password = $this -> getRequest()->getParam('pwd');
            if(!$this->view->error = Security::getInstance()->checkAdminLogin($username, $password)) {
                $role = Security::getInstance()->getUserRole($username);
                 if ($role == 'manager') {
		 if (isset($_SESSION['prev_admin_url']) && trim($_SESSION['prev_admin_url'])) {
                    $this->_redirect($_SESSION['prev_admin_url']);
                    $_SESSION['prev_admin_url'] = NULL;
                 }
                    $this->_redirect('/orders/ru/admin_orders/');
		}
                else if($role == 'admin') {
		if (isset($_SESSION['prev_admin_url']) && trim($_SESSION['prev_admin_url'])) {
                    $this->_redirect($_SESSION['prev_admin_url']);
                    $_SESSION['prev_admin_url'] = NULL;
                 }
                    $this->_redirect('/admin/');	//отправляем на управление содержимым
		}

            }
        }
    }



    public function indexAction() {
        $this->view->layout()->lang = $this->_getParam('lang', 'ru');
        $this->view->modules = Modules::getInstance()->GetModulesByGroupPriority();
       // echo var_dump(Modules::getInstance()->GetModulesByGroupPriority());
    }
}
