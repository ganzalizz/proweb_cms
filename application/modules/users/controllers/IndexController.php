<?php
class Users_IndexController extends Zend_Controller_Action {
    /**
     *
     * @var zend_layout
     */
    private $layout = null;

    /**
     *
     * @var string
     */
    private $lang = null;

    private $_count = null;

    private $_offset = null;

    private $_current_page = null;

    private $_cart = null;

    public function init() {
        /*$this->initView()
		$view = Zend_Layout::getMvcInstance()->getView();*/
        if ($this->_hasParam('logout')) {
            $this->_forward('logout');
        }

        $this->view->addHelperPath(Zend_Registry::get('helpersPaths') , 'View_Helper') ;
        $this->layout = $this->view->layout();
        $this->layout->setLayout( "users/userprofile" );
        $this->lang = $this->_getParam('lang', 'ru');
        $this->layout->lang = $this->lang;
        $id = $this->_getParam('id');
        $this->view->placeholder('id_page')->set($id);
        $page = Pages::getInstance()->find($id)->current();

        if (!is_null($page)) {
            if ($page->published==0) {
                $this->_redirect('/404');
            }
            $options  = PagesOptions::getInstance()->getPageOptions($id);
            $this->view->placeholder('title')->set($options->title);
            $this->view->placeholder('keywords')->set($options->keywords);
            $this->view->placeholder('descriptions')->set($options->descriptions);
            $this->view->placeholder('h1')->set($options->h1);
            $this->layout->page = $page;
            $this->view->current_page = $this->_current_page = $this->_getParam('page', 1);
            $this->view->onpage = $this->_count = 20;

        }
        if ($this->_hasParam('edit_profile')) {
            $this->_forward('editprofile');
        }

    }




    public function registrationAction() {
        $this->layout->setLayout( "front/default" );
        $ok = 0;
        Loader::loadCommon('Captcha');
        $captcha = new Captcha;
        $user =  SiteUsers::getInstance()->fetchNew();
        if ($this->_request->isPost()) {
            $validators = SiteUsers::getInstance()->getValidators();
            $data = $this->_getParam('form');
            $filter = FormFilter::getInstance()->getFilterInput($validators, $data);
            $deny_login = SiteUsers::getInstance()->checkField('login',$filter->getEscaped('login'));
            $deny_email = SiteUsers::getInstance()->checkField('email',$filter->getEscaped('email'));
            $check_pass = ($filter->getUnescaped('password')==@$data['confirm']);
            if ($filter->isValid() && $captcha->isValidate($data['captcha']) && $check_pass && !$deny_email && !$deny_login) {
                $fields = $filter->getEscaped();
                $fields['login'] = $filter->getUnescaped('login');
                $fields['password'] = $filter->getUnescaped('password');
                $fields['active'] = 1;
                $user->setFromArray(array_intersect_key($fields, $user->toArray()));
                $id = $user->save();
                if (!SiteAuth::getInstance()->checkLogin($fields['login'], $fields['password'])) {
                    $this->_redirect('/profile');
                }
                $ok= 1;
            } else {

                $errors =$filter->getMessages();
                $user->setFromArray(array_intersect_key($data, $user->toArray()));
                $ul_errors = array();
                foreach ((array)$errors as $key=>$err) {
                    $ul_errors[$key] = implode('<br>', $err);
                }
                if (!$captcha->isValidate($data['captcha'])) {
                    $ul_errors['Защита от автоматеческой регистрации'] = "Неправильно подсчитано выражение";
                }
                if ($deny_email) {
                    $ul_errors['Email'] = "Такой email уже занят";
                }
                if ($deny_login) {
                    $ul_errors['Логин'] = "Такой логин уже занят";
                }
                if (!$check_pass) {
                    $ul_errors['Пароль и подтверждение'] = "Пароль и подтверждение не совпадают";
                }
                $this->view->errors = $ul_errors;
            }
        }



        $this->view->ok = $ok;
        $this->view->captcha = $captcha->getOperation();
        $this->view->user = $user;


    }

    public function editprofileAction() {
        if (SiteAuth::getInstance()->getIdentity()) {
            $user = SiteAuth::getInstance()->getUser();
            $user = SiteUsers::getInstance()->find($user->id)->current();
            $ok = 0;
            $id_user = $this->_getParam('edit_profile',0);
            $this->view->placeholder('h1')->set('Редактировать персональные данные');

            if ($this->_request->isPost()) {
                $validators = SiteUsers::getInstance()->getValidators();
                unset($validators['Логин']);
                unset($validators['Пароль']['presence']);
                $validators['Пароль']['allowEmpty'] = true;
                unset($validators['Условия регистрации']);

                $data = $this->_getParam('form');
                $filter = FormFilter::getInstance()->getFilterInput($validators, $data);
                $deny_email = SiteUsers::getInstance()->checkField('email',$filter->getEscaped('email'), $user->id);
                if ($filter->isValid() && !$deny_email) {
                    $data = $filter->getEscaped();
                    $data['login'] = $user->login;
                    $data['added'] = $user->added;
                    if (!trim($data['password'])) unset($data['password']);
                    $data['active'] = 1;
                    $data['subscribe'] = isset($data['subscribe'])?1:0;
                    $user->setFromArray(array_intersect_key($data, $user->toArray()));
                    $id = $user->save();

                    SiteAuth::getInstance()->checkLogin($user->login, $user->password);

                    $ok= 1;
                } else {
                    $errors =$filter->getMessages();
                    $data = $this->_getAllParams();
                    $user->setFromArray(array_intersect_key($data, $user->toArray()));
                    $ul_errors = array();
                    foreach ((array)$errors as $key=>$err) {
                        $ul_errors[$key] = implode('<br>', $err);
                    }
                    if ($deny_email) {
                        $ul_errors['Email'] = "Такой email уже занят";
                    }
                    $this->view->errors = $ul_errors;
                }
            }
            $this->view->ok = $ok;
            $this->view->user = $user;
            $this->layout->current_menu='editprofile';
        } else {
            $this->_redirect('/login');
        }



    }

    public function loginAction() {
        if (SiteAuth::getInstance()->checkUser()) $this->_redirect('/profile');
        $ul_errors = array();

        $this->layout->setLayout( "front/default" );

        if ($this->_request->isPost()) {
            $login = $this->_getParam('login');
            $pass = $this->_getParam('password');
            if ($login && $pass) {
                SiteAuth::getInstance()->logout();
            }
            if ($login && $pass && !SiteAuth::getInstance()->checkLogin($login, $pass)) {
                $this->_redirect('/profile');

            } else {
                $ul_errors['Ошибка'] = "Неверный логин или пароль";
            }
            if (!$login) {
                $ul_errors['Логин'] = "Поле обязательное для заполнения";
            }
            if (!$pass) {
                $ul_errors['Пароль'] = "Поле обязательное для заполнения";
            }

            $this->view->errors = $ul_errors;
        }



    }

    public function logoutAction() {
        SiteAuth::getInstance()->logout();
        $this->_redirect('/login');
    }


    public function userareaAction() {
        if (SiteAuth::getInstance()->getIdentity()) {
            $user = SiteAuth::getInstance()->getUser();
            if (!is_null($user)) {
                $this->_redirect('/ordershistory');
            }

        } else {
            $this->_redirect('/login');
        }
    }

    public function forgotAction() {
        if (SiteAuth::getInstance()->checkUser()) $this->_redirect('/profile');
        $errors = array();
        if ($this->_request->isPost()) {
            $email = $this->_getParam('email');
            if ($email!='') {
                $user = SiteUsers::getInstance()->fetchRow("email='$email'");
                if (!is_null($user)) {
                    $template_row = FeedbackTemplates::getInstance()->fetchRow("system_name='forgot_pass'");
                    $template = '';
                    if (!is_null($template_row)) {
                        $template = $template_row->content;
                        $template = str_replace('{login}', $user->login, $template);
                        $template = str_replace('{password}', $user->password, $template);
                        Loader::loadCommon('Mail');
                        Mail::send($email, $template, 'Восстановление пароля', $user->first_name." ".$user->last_name);
                        $this->view->ok=1;
                    } else {
                        $errors[]='Сервис временно недоступен';
                    }

                }else {
                    $errors[]='Указанный Email в базе не найден';
                }
            } else {
                $errors[]='Не заполнено поле Email';
            }
        }
        if (count($errors)) {
            $this->view->errors = $errors;
        }
    }

    public function ordershistoryAction() {
        if (!SiteAuth::getInstance()->getIdentity()) $this->_redirect('/login');
        if (is_null($user = SiteAuth::getInstance()->getUser())) $this->_redirect('/profile');

        $this->view->orders = Orders::getInstance()->getAllUserOrders($user->id);
        $this->view->statuses = Orders::getInstance()->getStatusTitles();
        $this->layout->current_menu='ordershistory';
    }

    public function orderdetailsAction() {
        if (!SiteAuth::getInstance()->getIdentity()) $this->_redirect('/login');
        if (is_null($user = SiteAuth::getInstance()->getUser())) $this->_redirect('/profile');

        if ($this->_request->isPost() && !$this->_hasParam('repeat')) {
            Orders::getInstance()->addComment(
                    (int)$this->_getParam('order_id'),
                    $this->_getParam('comment_title', ''),
                    $this->_getParam('comment_text', '')
            );
            $order = Orders::getInstance()->find((int)$this->_getParam('order_id'))->current();

            if ($order && $manager = Users::getInstance()->find($order->id_manager)->current()) {
                $fields = array();
                $fileds['order_number'] = (int)$this->_getParam('order_id');
                $fileds['theme'] = $this->_getParam('comment_title', '');
                $fileds['comment'] = $this->_getParam('comment_text', '');
                $template = FeedbackTemplates::getInstance()->getBySystemName('new_order_comment');

                $subject = $template['name'];
                $template = $template['content'];

                foreach ($fileds as $key=>$param) {
                    $template = str_replace('{'.$key.'}', $param, $template);
                }
                $template = preg_replace('/{.+}/Usi', '', $template);
                Loader::loadCommon('Mail');
                $from = '';
                $recipient = 'Новый отзыв';
                $email = $manager->email;
                Mail::send($email, $template, $from, $subject, $recipient);
            }

        }

        if (is_null($item = Orders::getInstance()->getItemToView((int)$this->_getParam('order_id', 0)))) $this->_redirect('/ordershistory');
        $item->content = unserialize( $item->content );

        $this->_cart = new Zend_Session_Namespace('cart');
        if (!isset($this->_cart->products)) {
            $this->_cart->products = array();
        }


        if ($this->_request->isPost() && $this->_hasParam('repeat')) {
            foreach ($item->content as $key=>$product) {
                if (substr_count($key, "constr_")!=0) {
                    $this->repeatCustomOrder($key, $product);
                } else {
                    $this->repeatOrder($product['id_product'], $product['id_price']);
                }
            }
            $this->view->ok = 1;
        }
        $this->view->user = $user;
        $this->view->order = $item;
    }

    /**
     * Повторение заказа (блюдо не из конструктора)
     * @param int $product_id
     * @param int $price_id
     */
    private function repeatOrder($product_id, $price_id) {
        $product_id = (int)$product_id;
        $price_id = (int)$price_id;
        $key = $product_id;

        if ($price_id) {
            $key .= '_'.$price_id;
        }

        if ($product_id && !array_key_exists($key ,$this->_cart->products)) {

            $product = Catalog_Product::getInstance()->getPublicItem($product_id);

            $price = null;
            $title = null;

            if ($price_id) {
                $price = Catalog_Product_Options_Prices::getInstance()->getPriceById($price_id, $product_id);
                $title = Catalog_Product_Options_Values::getInstance()->getTitleByPriceId($price_id);

            }           

            if ($product!=null) {
                $this->_cart->products[$key] = array(
                        'title' 		=> $title!=null ? $title : $product->title,
                        'count' 		=> 1,
                        'price' 		=> $price!=null ? $price : $product->price,
                        'total_price'           => $price!=null ? $price : $product->price,
                        'id_product'            => $product_id,
                        'id_price'		=> $this->_getParam('id_price')

                );
            }
        } elseif ($product_id && array_key_exists($key ,$this->_cart->products)) {
            $count = $this->_cart->products[$key]['count'];
            $price = $this->_cart->products[$key]['price'];
            $count++;
            $this->_cart->products[$key]['count'] = $count;
            $this->_cart->products[$key]['total_price'] = $count*$price;
        }
    }

    /**
     * Повторение заказа (блюдо из конструктора)
     * @param int $product_id
     * @param int $price_id
     */
    private function repeatCustomOrder($key, $product) {
        if (isset($key) && array_key_exists($key ,$this->_cart->products)) {
            unset($this->_cart->products[$key]);
        }
        $order = $product['order'];
        
        $price = 0;
        foreach ($order['items'] as $item) {
            $price += (int)Constructor_Prices::getInstance()->getPrice($order['type_id'], $item, $order['size']);
        }

        if (!array_key_exists($key ,$this->_cart->products)) {
            $title = Constructor_Sizes::getInstance()->getNameById($order['size']).' '.Constructor_Types::getInstance()->getNameById($order['type_id']);
            if (count($order)!=null) {
                $this->_cart->products[$key] = array(
                    'title' 		=> $title!=null ? $title : 'Свое блюдо',
                    'count' 		=> $order['count'],
                    'price' 		=> $price,
                    'total_price' 	=> $price*$order['count'],
                    'id_product'	=> $key,
                    'id_price'		=> $price,
                    'order'             => $order
                );
            }
        }elseif (array_key_exists($key ,$this->_cart->products)) {
            $count = $this->_cart->products[$key]['count'];
            $price = $this->_cart->products[$key]['price'];
            $count++;
            $this->_cart->products[$key]['count'] = $count;
            $this->_cart->products[$key]['total_price'] = $count*$price;
        }
    }


}
