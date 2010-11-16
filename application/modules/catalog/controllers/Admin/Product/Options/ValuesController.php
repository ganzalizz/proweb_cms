<?php

/**
 * Catalog_Admin_Product_OptionsController
 *
 * @author Vitali
 * @version
 */

class Catalog_Admin_Product_Options_ValuesController extends MainAdminController {

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

    private $_page = null;
    /**
     * offset
     *
     * @var int
     */
    private $_offset = null;
    
    private $_id_product = null;
    
	/**
	 * 
	 * @var Catalog_Product_Row
	 */
    private $_product = null;
    /**
     * 
     * @var int
     */
    private $_id_option = null;
    /**
     * 
     * @var object
     */
    private $_option = null;
    /**
     * параметры передаваемые через ссылку
     * @var unknown_type
     */
    private $_url_params = null;
    /**
     * 
     * @var Catalog_Product_Options_Values
     */
    private $_model = null;

   

    public function init() {
        $this->initView();
        $this->layout = $this->view->layout();       
		$lang = $this->_getParam('lang','ru');
		$this->_id_product = $this->view->id_product = $this->_getParam('id_product');
		$this->_product = $this->view->product = Catalog_Product::getInstance()->find((int)$this->_id_product)->current();
		$this->_id_option = $this->view->id_option = $this->_getParam('id_option');
		$this->_option = Catalog_Product_Options::getInstance()->find($this->_id_option)->current();
		
		if (is_null($this->_product) || is_null($this->_id_option)){
			$this->_redirect('/catalog/'.$lang."/admin_division");
		}
		$this->_url_params  = "/id_product/".$this->_id_product."/id_option/".$this->_id_option;
		$this->view->url_params = $this->_url_params;						
		$this->_model = Catalog_Product_Options_Values::getInstance();
       
    
        $this->layout->title = "Каталог, опции товара <strong>".$this->_product->title."</strong>";
       
        $this->view->currentModule = $this->_curModule = '/'.$this->getRequest()->getModuleName().'/'.$lang.'/'.$this->getRequest()->getControllerName();
        $this->_page = $this->_getParam('page', 1);
        $this->_offset =($this->_page-1)*$this->_onpage;
        $this->view->current_page = $this->_page;
        $this->view->onpage = $this->_onpage;
         
    }

    /**
     * The default action - show the home page
     */
    public function indexAction() {    	
        $this->layout->action_title = "Значения Опции <strong>".$this->_option->title."</strong>";
        $this->view->total = $this->_model->getProductOptionValues($this->_id_product, $this->_id_option)->count();
        $this->view->all = $this->_model->getProductOptionValues($this->_id_product, $this->_id_option);
      	 
       
    }
    /**
     * создать, редактировать
     */
    public function editAction(){
    	if ($id = $this->_getParam('id')){
    		$item = $this->_model->find($id)->current();
    	} else {
    		$item = $this->_model->fetchNew();
    	}
    	
    	
    	
    	if ($this->_request->isPost()){
    		$data = $this->_getAllParams();    		
    		$item->setFromArray(array_intersect_key($data, $item->toArray()));
    		$item->save();
    		$price = $data['price'];
    		if ($price){
    			Catalog_Product_Options_Prices::getInstance()->updateValuePrice(
    				$item->id,
    				$this->_id_product,
    				$this->_id_option,
    				$price
    			);
    		}
    		/**
    		 * удаление кэша
    		 */
    		Catalog_Product_Options_Enabled_Cache::clear();
    		$this->redirectToIndex();
    	}
    	if ($item->id){
    		$this->view->price = Catalog_Product_Options_Prices::getInstance()->getValuePrice($item->id);
    	}
    	$fck1 = $this->getFck('description', '90%', '150','Basic');	
    	$fck1->Value = $item->description;
		$this->view->fck_description= $fck1;	    	
    	$this->view->item = $item;
    	
    	
    }
    

  
	/**
	 * удаление 
	 */
    public function deleteAction() {
        $id = $this->_getParam('id',0);
        $item = $this->_model->find($id)->current();
        if (!is_null($item)) {
        	Catalog_Product_Options_Prices::getInstance()->deleteValuePrice($item->id);
            $item->delete();
        }
        Catalog_Product_Options_Enabled_Cache::clear();
        $this->redirectToIndex();
    }
    
    private function redirectToIndex(){
    	$this->_redirect($this->_curModule."/index" . $this->_url_params );
    }

   
    
	
    
    
}