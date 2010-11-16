<?php
class constructor_IndexController extends Zend_Controller_Action {
    /**
     * Шаблон страницы
     * @var zend_layout
     */
    private $layout = null;

    /**
     * Язык страницы
     * @var string
     */
    private $lang = null;

    /**
     * Объект страницы
     * @var Pages_row
     */
    private  $_page = null;

    
    public function init() {        
        $id = $this->_getParam('id');
        $page = Pages::getInstance()->getPage($this->_getParam('id'));     
        
        $this->lang = $this->_getParam('lang', 'ru');
        $this->_page = $page;
        
        $this->view->content = $page->content;
        $this->view->options = $options = PagesOptions::getInstance()->getPageOptions($id);
        $this->view->placeholder('title')->set($options->title);
        $this->view->placeholder('keywords')->set($options->keywords);
        $this->view->placeholder('descriptions')->set($options->descriptions);
        $this->view->placeholder('h1')->set($options->h1);
        $this->view->placeholder('id_page')->set($id);

        $this->layout = $this->view->layout();
        $this->layout->setLayout("front/constructor");
        $this->layout->current_type = 'pages';
        $this->layout->lang = $this->lang;
        $this->layout->page = $this->_page;
        $this->layout->id_object = $this->_page->id;

        $dish = $this->_getParam('dish', null);
        if ($dish){
            $this->_forward('edit');
        };

    }
    
    /**
     * Открытие страницы с переданным id
     */
    public function indexAction() {
        if ($this->_request->isPost()){           
            $this->_forward('addcustom', 'cart', 'catalog', array('act'=>'addcustom'));
        }

        $type_id = $this->_getParam('type_id', 0);
        $select_types = Constructor_Types::getInstance()->getAllActiveTypesForSelect();

        if (!$type_id)
            $type = Constructor_Types::getInstance()->getFirstActiveType();
        else
            $type = Constructor_Types::getInstance()->getActiveTypeById($type_id);
        

		if(!is_null($type)){	
			Constructor_Sizes::getInstance()->insertSizesToTypeRow($type);
			Constructor_Groups::getInstance()->insertGroupsRowsetTypeRow($type);
			Constructor_Groups_Items::getInstance()->insertItemsToGroupRowset($type);
			Constructor_Prices::getInstance()->insertPricesToItemsRowset($type);

			$this->view->select_types = $select_types;
			$this->view->selected_type = $type_id;
			$this->view->type = $type;
		}
		
    }

    public function editAction(){
        $dish = $this->_getParam('dish', null);
        if (!$dish) $this->_redirect('cart');
        $this->_cart = new Zend_Session_Namespace('cart');
        if ($this->_request->isPost()){
            $this->_forward('addcustom', 'cart', 'catalog', array('act'=>'addcustom'));
            return;
        }

        $type_id = $this->_cart->products[$dish]['order']['type_id'];
        $select_types = Constructor_Types::getInstance()->getAllActiveTypesForSelect();

        if (!$type_id)
            $type = Constructor_Types::getInstance()->getFirstActiveType();
        else
            $type = Constructor_Types::getInstance()->getActiveTypeById($type_id);

        Constructor_Sizes::getInstance()->insertSizesToTypeRow($type);
        Constructor_Groups::getInstance()->insertGroupsRowsetTypeRow($type);
        Constructor_Groups_Items::getInstance()->insertItemsToGroupRowset($type);
        Constructor_Prices::getInstance()->insertPricesToItemsRowset($type);

        $this->view->select_types = $select_types;
        $this->view->selected_type = $type_id;
        $this->view->type = $type;
        
        $this->view->defaults = $this->_cart->products[$dish]['order']['items'];
        $this->view->size = $this->_cart->products[$dish]['order']['size'];
        $this->view->count = (int)$this->_cart->products[$dish]['order']['count'];
        $this->render('index');
    }

   
}
