<?php
class Catalog_CartController extends Zend_Controller_Action {
/**
 * Шаблон страницы
 * @var zend_layout
 */
    private $layout = null;

    private $_cart = null;

    /**
     * Объект страницы
     * @var Pages_row
     */
    private $_page = null;

    private $_id_product = null;


    public function init() {
        $this->_id_product = $this->_getParam('id_product');
        $this->_cart = new Zend_Session_Namespace('cart');
        if (!isset($this->_cart->products)) {
            $this->_cart->products = array();
        }
        $action = $this->_getParam('act');
        
       
        
        // process action
        switch ($action) {
            case 'add': $this->_forward('add')
                ;
                break;
            case 'remove': $this->_forward('remove')
                ;
                break;
            case 'recalc': $this->_forward('recalc')
                ;
                break;
            case 'clear': $this->_forward('clear')
                ;
                break;
            case 'change': $this->_forward('change')
                ;
                break;
            case 'addcustom':
                $this->_forward('addcustom');
                break;
            case 'getcount':            	 
                $this->_forward('cartcount');
                break;

            default: $this->_forward('index')
                ;
                break;
        }
		
        $this->view->addHelperPath( Zend_Registry::get( 'helpersPaths' ), 'View_Helper' );
        $id = $this->_getParam( 'id' );
        $page = Pages::getInstance()->getPage( $this->_getParam( 'id' ) );
        if (is_null( $page ) || $page->published == '0') {
            $this->_redirect( '/404' );
        }
        $this->_page = $page;
        $this->layout = $this->view->layout();
        $this->layout->setLayout( "front/cart" );
        $this->layout->current_type = 'pages';
        $this->layout->page = $this->_page;

        $this->view->content = $page->content;
        $options = PagesOptions::getInstance()->getPageOptions( $id );
        $this->view->placeholder( 'title' )->set( $options->title );
        $this->view->placeholder( 'keywords' )->set( $options->keywords );
        $this->view->placeholder( 'descriptions' )->set( $options->descriptions );
        $this->view->placeholder( 'h1' )->set( $options->h1 );




    }

    /**
     * просмотр корзины
     */
    public function indexAction() {
        $this->view->catalog_path = SiteDivisionsType::getInstance()->getPagePathBySystemName('division');
       
        if (is_array($this->_cart->products)) {
            $order = Orders::getInstance()->fetchNew();           
            if (SiteAuth::getInstance()->getIdentity()) {
                $user = SiteAuth::getInstance()->getUser();
                $order = $this->setUserData($order, $user);

            }
            if ($this->_request->isPost()) {
                $validators = Orders::getInstance()->getValidators();
                $data = $this->_getAllParams();
                unset($data['id']);
                $filter = FormFilter::getInstance()->getFilterInput($validators, array_intersect_key($data, $order->toArray()));
                $order->setFromArray( array_intersect_key($data, $order->toArray()));
                if ($filter->isValid()) {
                    $order->price = $this->setTruePrices($this->_cart->products);
                    if ($order_id = Orders::getInstance()->AddOrder($order, $this->_cart->products)) {
                        $this->_cart->products = null;
                        $this->view->ok = 1;
                    }
                } else {
                    $errors =$filter->getMessages();

                    $ul_errors = array();
                    foreach ((array)$errors as $key=>$err) {
                        $ul_errors[$key] = implode('<br>', $err);
                    }
                    $this->view->errors = $ul_errors;
                }

            }
             $this->view->products = $this->_cart->products;
            $this->view->order = $order;
           

        }
    }

    /**
     * добавление товара в корзину
     * @todo другой title для товаров с id_price
     */
    public function addAction() {
        $key = $this->_id_product;
        $id_price = 0;
        if ($id_price = $this->_getParam('id_price')) {
            $key .= '_'.$id_price;
        }
        if ($this->_id_product && !array_key_exists($key ,$this->_cart->products)) {
            $product = Catalog_Product::getInstance()->getPublicItem($this->_id_product);
            $price = null;
            $title = null;

            if ($id_price) {
                $price = Catalog_Product_Options_Prices::getInstance()->getPriceById($id_price,$this->_id_product );
                $title = Catalog_Product_Options_Values::getInstance()->getTitleByPriceId($id_price);

            }
            if ($product!=null) {
                $this->_cart->products[$key] = array(
                    'title' 		=> $title!=null ? $product->title." ".$title : $product->title,
                    'count' 		=> 1,
                    'price' 		=> $price!=null ? $price : $product->price,
                    'total_price' 	=> $price!=null ? $price : $product->price,
                    'id_product'	=> $this->_id_product,
                    'id_price'		=> $this->_getParam('id_price')

                );
                echo 'ok';
            }
        }elseif ($this->_id_product && array_key_exists	($key ,$this->_cart->products)) {
            $count = $this->_cart->products[$key]['count'];
            $price = $this->_cart->products[$key]['price'];
            $count++;
            $this->_cart->products[$key]['count'] = $count;
            $this->_cart->products[$key]['total_price'] = $count*$price;
            echo 'ok';
        }else {
            echo 'err';
        }
        exit;
    }


    /**
     * добавление товара из конструктор в корзину
     */
    public function addcustomAction() {
        $data = $this->_getAllParams();
        if (isset($data['dish']) && array_key_exists($data['dish'] ,$this->_cart->products)) {
            unset($this->_cart->products[$data['dish']]);
        }
        $order = array();
        $order['type_id'] = $data['type'];
        $order['size'] = $data['sizes'];
        if (isset($data['item']) && count($data['item'])) {
            $order['items'] = $data['item'];
        }
        if (isset($data['item_radio']) && count($data['item_radio'])) {
            $order['items'] = array_merge_recursive($data['item_radio'], $order['items']);
        }
        $order['count'] = (int)$data['count'];
        $custom_id = $order['type_id'].$order['size'];
        foreach ($order['items'] as $item) {
            $custom_id .= $item;
        }
        $price = 0;
        foreach ($order['items'] as $item) {
            $price += (int)Constructor_Prices::getInstance()->getPrice($order['type_id'], $item, $order['size']);     
        }
        $price = (int)$price + (int)Constructor_Sizes::getInstance()->getSizePrice($order['size']);
        $key = "constr_".$custom_id;
        $id_price = 0;
        if ($id_price = $this->_getParam('id_price')) {
            $key .= '_'.$id_price;
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
                echo 'ok';
            }
        }elseif (array_key_exists($key ,$this->_cart->products)) {
            $count = $this->_cart->products[$key]['count'];
            $price = $this->_cart->products[$key]['price'];
            $count++;
            $this->_cart->products[$key]['count'] = $count;
            $this->_cart->products[$key]['total_price'] = $count*$price;
            echo 'ok';
        }else {
            echo 'err';
        }
        $this->_redirect('cart');
    }

    /**
     * удаление товара из корзины
     */
    public function removeAction() {
        if(array_key_exists($this->_id_product ,$this->_cart->products)) {
            unset($this->_cart->products[$this->_id_product]);
            echo 'ok';
        }
        exit;
    }
    /**
     * изменение количества товаров в корзине
     */
    public function recalcAction() {

    }
    /**
     * очитска корзины
     */
    public function clearAction() {
        $this->_cart->products = null;
        $this->_redirect('/'.$this->_page->path);

    }

    public function changeAction() {
        if ($this->_id_product && $count = $this->_getParam('count')) {
            if(array_key_exists($this->_id_product ,$this->_cart->products)) {
                $price = $this->_cart->products[$this->_id_product]['price'];
                $this->_cart->products[$this->_id_product]['count'] = $count;
                $this->_cart->products[$this->_id_product]['total_price'] = $count*$price;
                echo 'ok';
            }
        }
        exit;
    }
    
    
    public function cartcountAction(){ 
    	if ($this->_cart!=null){
    		echo count($this->_cart->products);
    	} else {
    		echo 0;
    	}    	
    	exit;
    }

    /**
     *
     * @param Orders $order
     * @param object $user
     * @return Zend_Db_Table_Row
     */
    private function setUserData($order, $user) {
        $order->id_user = $user->id;
        $order->discount = $user->discount;
        $order->user_name = $order->user_name=='' ? $user->first_name : $order->user_name ;
        $order->user_street = $order->user_street=='' ?  $user->street : $order->user_street;
        $order->user_house = $order->user_house=='' ?  $user->house : $order->user_house;
        $order->user_house_block = $order->user_house_block=='' ?  $user->house_block : $order->user_house_block;
        $order->user_flat = $order->user_flat=='' ?  $user->flat : $order->user_flat;
        $order->user_phone = $order->user_phone=='' ? $user->mobile_phone : $order->user_phone;
        $order->user_email = $order->user_email=='' ? $user->email : $order->user_email;
        return $order;

    }

    /**
     * устанавливает цены из базы для товаров в корзине
     */
    private function setTruePrices() {
        $order_price = 0;
        if ($this->_cart->products) {
            foreach ($this->_cart->products as $key=> $item) {
                $id_product = $item['id_product'];
                $id_price = $item['id_price'];
                $price = 0;
                if ($id_price && $id_product) {
                    $price = Catalog_Product_Options_Prices::getInstance()->getPriceById($id_price, $id_product);

                } elseif ($id_product) {
                    $price = Catalog_Product::getInstance()->getPrice($id_product);
                }
                if ($price) {
                    $this->_cart->products[$key]['price'] = $price;
                    $this->_cart->products[$key]['total_price'] = $price*$item['count'];
                    $order_price+=$price*$item['count'];
                } elseif (substr_count($id_product, "constr_")) {
                    $order_price += $this->_cart->products[$key]['total_price'];
                } else {
                    unset($this->_cart->products[$key]);
                }
            }
        }
        return $order_price;
    }




}
