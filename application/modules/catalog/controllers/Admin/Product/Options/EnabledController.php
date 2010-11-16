<?php

/**
 * Catalog_Admin_Product_OptionsController
 *
 * @author Vitali
 * @version
 */

class Catalog_Admin_Product_Options_EnabledController extends MainAdminController {

    /**
     * @var string
     */
    private $_curModule = null;
    /**
     * items per page
     *
     * @var int
     */
    private $_onpage = 20;

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
     * @var Catalog_Product_Options_Enabled
     */
    private $_model = null;

   

    public function init() {
        $this->initView();
        $this->layout = $this->view->layout();       
		$lang = $this->_getParam('lang','ru');
		$this->_id_product = $this->view->id_product = $this->_getParam('id_product');
		$this->_product = $this->view->product = Catalog_Product::getInstance()->find((int)$this->_id_product)->current();
		if (is_null($this->_product)){
			$this->_redirect('/catalog/'.$lang."/admin_division");
		}
		$this->_model = Catalog_Product_Options_Enabled::getInstance();
       
    
        $this->layout->title = "Каталог, список опций товара <strong>".$this->_product->title."</strong>";
       
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
        $this->layout->action_title = "Список опций";
        $this->view->total = $this->_model->getProductOptions($this->_id_product)->count();
        $this->view->all = $this->_model->getProductOptions($this->_id_product);
      	 
       
    }
    /**
     * создать, редактировать
     */
    public function editAction(){
    	if ($id = $this->_getParam('id')){
    		$item = $this->_model->find($id)->current();
    	} else {
    		$this->redirectToIndex();
    	}
    	
    	$option = Catalog_Product_Options::getInstance()->find($item->id_option)->current();
    	$select_options = array();
    	if ($option!=null){
    		
    			$select_options[$option->id] = $option->title;
    		
    	}
    	
    	if ($this->_request->isPost()){
    		$data = $this->_getAllParams();
    		
    		$item->setFromArray(array_intersect_key($data, $item->toArray()));
    		$item->save();
    		/**
    		 * удаление кэша
    		 */
    		Catalog_Product_Options_Enabled_Cache::clear();
    		$this->redirectToIndex();
    	}
    	$this->view->options = $select_options;
    	$this->view->item = $item;
    	
    	
    }
    /**
     * создать, редактировать
     */
    public function addAction(){
    	
    	$item = $this->_model->fetchNew();
    	$options = Catalog_Product_Options::getInstance()->getOptionsToProduct($this->_id_product);
    	$select_options = array();
    	if (is_array($options)){
    		foreach ($options as $option){
    			$select_options[$option['id']] = $option['title'];
    		}
    	}
    	
    	if ($this->_request->isPost()){
    		$data = $this->_getAllParams();
    		$item->setFromArray(array_intersect_key($data, $item->toArray()));
    		$item->save();
    		/**
    		 * удаление кэша
    		 */
    		Catalog_Product_Options_Enabled_Cache::clear();
    		
    		$this->redirectToIndex();
    	}
    	$this->view->options = $select_options;
    	$this->view->item = $item;
    	
    	
    }

  
	/**
	 * удаление 
	 */
    public function deleteAction() {
        $id = $this->_getParam('id',0);
        $item = $this->_model->find($id)->current();
        if (!is_null($item)) {
        	Catalog_Product_Options_Prices::getInstance()->deletePricesByOption($item->id_option);
        	Catalog_Product_Options_Values::getInstance()->deleteByOption($item->id_option);
            $item->delete();
        }
        Catalog_Product_Options_Enabled_Cache::clear();
        
        $this->redirectToIndex();
    }
    
    private function redirectToIndex(){
    	$this->_redirect($this->_curModule."/index/id_product/".$this->_id_product);
    }

   
    
	
    
    
}