<?php

/**
 * Admin_BlocksController
 *
 * @author Grover
 * @version 1.0
 */

class Orders_Admin_OrdersController extends MainAdminController {

/**
 * @var string
 */
    private $_curModule = null;
    /**
     * items per page
     *
     * @var int
     */
    private $_onpage = 50;

    /**
     * items per page
     *
     * @var int
     */
    private $_lang = null;

    private $_page = null;
    /**
     * offset
     *
     * @var int
     */
    private $_offset = null;

    public function init() {
    //$this->initView();
        $this->layout = $this->view->layout();
        $this->layout->title = "Заказы";
        $this->view->caption = 'Список заказов';
        $lang = $this->_getParam('lang','ru');

        $this->view->currentModule = $this->_curModule = SP.$this->getRequest()->getModuleName().SP.$lang.SP.$this->getRequest()->getControllerName();
        $this->_page = $this->_getParam('page', 1);
        $this->_offset =($this->_page-1)*$this->_onpage;
        $this->view->current_page = $this->_page;
        $this->view->onpage = $this->_onpage;
    }


    public function indexAction() {
        $session_orders = new Zend_Session_Namespace('orders');
        $search_params = array();
        if ($this->_getParam('clear_search')) {
            $session_orders->search = array();
        }
        if ($this->_request->isPost()) {
            if($search_params = $this->_getParam('search')) {
                $session_orders->search = $search_params;
            }
        }
        if (isset($session_orders->search)) {
            $search_params = $session_orders->search;
        }
        foreach ($search_params as $key=>$param) {
            $this->view->assign($key, $param);
        }
        $this->view->all = Orders::getInstance()->getAll($search_params, $this->_onpage, $this->_offset);
        $this->view->total = Orders::getInstance()->getAll($search_params)->count();

        $this->view->status_titles = Orders::getInstance()->getStatusTitles();
        $this->view->order_time = Blocks::getInstance()->getContentBySystemName('order_time');
        $managers = Users::getInstance()->getUsersByRole(ROLE_MANAGER);
        $managers_array[0]='';
        foreach ($managers as $manager) {
            $managers_array[$manager->id] = $manager->firstName." ".$manager->lastName;
        }
        $this->view->managers = $managers_array;
    }

    public function viewAction() {
    //$this->_helper->layout()->disableLayout();  
        $id = $this->getParam( 'item_id' );
        $ok = 0;
        if ($id) {
            $change = 0;
           
            if ($this->_request->isPost()) {
                if (! $this->_getParam( 'no_change_status' )) {
                    Orders::getInstance()->SetNextStatus( $id );
                }
                
                
                $this->updateOrder();
                
                $ok = 1;
            }
            $item = Orders::getInstance()->getItemToView( $id );
            $item->content = unserialize( $item->content );
            $products = $item->content;
            //print_r($products); 
            foreach ($products as &$product) {
                if (isset($product['order']['items']) && count($product['order']['items'])) {                	
                    $product['order'] = Constructor_Groups_Items::getInstance()->getItemsByIdsArray($product['order']);
                    $product['order']['size_price'] = Constructor_Sizes::getInstance()->getSizePrice($product['order']['size']);
                    $product['order']['size'] = Constructor_Sizes::getInstance()->getNameById($product['order']['size']);
                    
                }
                
            }
             //print_r($products);
            $item->content = $products;
            $this->view->item = $item;
            $this->view->ok = $ok;
            $this->view->status_titles = Orders::getInstance()->getStatusTitles();

            if ($this->_hasParam('print')) {
                $this->layout->disableLayout();
                $this->render('printorder');
            }

        }
    }

    private function updateOrder() {
        $id = $this->getParam( 'item_id' );        
        $order = Orders::getInstance()->find($id)->current();    
        $change_count = $this->_getParam('products_count', array());
        $to_delete = $this->_getParam('products_delete', array());  
        $to_del_items = $this->_getParam('to_del_items', array());   
        //print_r($to_delete); exit;
        
        if ($order!=null) {
        	$products = unserialize($order->content);
        	
        	if (is_array($products)){
        		$order_price = 0;
        		foreach ($products as $key=>$data){
        			// удаление товара из заказа
        			if (in_array($key, $to_delete)){
        				unset($products[$key]);
        				continue;
        				
        			} 
        			
        			if ($to_del_items && array_key_exists('order', $data)){
        				 $products[$key]['order']['items']  = array_diff($data['order']['items'], $to_del_items);
        				
        			}
        			
        			if (array_key_exists('order', $data)){        				
        				$size_price = Constructor_Sizes::getInstance()->getSizePrice($data['order']['size']);
        				
        				$item_price = Constructor_Groups_Items::getInstance()->getTotalPriceByItems($products[$key]['order']);
        				$products[$key]['price'] = $size_price + $item_price;
        			}
        			
        			if (array_key_exists($key, $change_count)) {
        				if ($change_count[$key]>0){
        					$products[$key]['count'] = $change_count[$key];
        					$products[$key]['total_price'] = $change_count[$key]*$products[$key]['price'];
        				} else {
        					unset($products[$key]);
        				}
        			}
        			
        			
        			
        			if (array_key_exists($key, $products)){
        				$order_price +=  $products[$key]['total_price'];
        			}
        		}
        		$order->price = $order_price;
        	}
        	
        	//print_r($products);
        	$order->content = serialize($products);
        	$data = $this->_getAllParams();
            $order->setFromArray(array_intersect_key($data, $order->toArray()));
            $order->save();           
       		 if ($order->id_user!=''){
                	Orders::getInstance()->setRegularCustomer($order->id_user);
                }
        }
    }



}