<?php

/**
 * Catalog_Admin_Product_OptionsController
 *
 * @author Vitali
 * @version
 */

class Catalog_Admin_Product_OptionsController extends MainAdminController {

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

    

   

    public function init() {
        $this->initView();
        $this->layout = $this->view->layout();       
		$lang = $this->_getParam('lang','ru');
       
    
        $this->layout->title = "Каталог, список опций";
       
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
        
    	if ($this->_request->isPost()){			
			$array_titles = $this->_getParam('titles');
			$items = $this->_getParam('items');
			if ($array_titles){
				Catalog_Product_Options::getInstance()->AddItems($array_titles);
			}
			if ($items){
				Catalog_Product_Options::getInstance()->processItems($this->_getParam('operation'), $items);
			}
			/**
    		 * удаление кэша
    		 */
    		Catalog_Product_Options_Enabled_Cache::clear();
			
				
    	}
		
              
        $this->view->total = Catalog_Product_Options::getInstance()->fetchAll()->count();
        $this->view->all = Catalog_Product_Options::getInstance()->fetchAll(null, 'title ASC' , $this->_onpage, $this->_offset);
      	 
       
    }
	
	
	/**
     * создание редактирование элемента
     */
    public function editAction(){
    	$id = $this->_getParam('id');
    	$is_new = 0;
    	if ($id){
    		$item = Catalog_Product_Options::getInstance()->find($id)->current();
    	} else {
    		$item = Catalog_Product_Options::getInstance()->fetchNew();
    		$is_new = 1;
    	}
    	
    	if ($this->getRequest()->isPost()){
    		if ($this->_getParam('title')){	
	    		$data = $this->_getAllParams();
	    		$item->setFromArray(array_intersect_key($data, $item->toArray()));
	    		$item->save();	    		
	    		$this->view->ok = 1;
    		} else {
    			$this->view->ok = 0;
    		}
    	}
    	$this->view->item = $item;
    	//$this->view->types = Catalog_Product_Options::getInstance()->getTypes();
    }

  

    public function deleteAction() {
        $id = $this->_getParam('id',0);
        $item = Catalog_Product_Options::getInstance()->find($id)->current();
        if (!is_null($item)) {
        	Catalog_Product_Options_Enabled::getInstance()->deleteByOption($id);
        	Catalog_Product_Options_Values::getInstance()->deleteByOption($id);
        	Catalog_Product_Options_Prices::getInstance()->deletePricesByOption($id);
            $item->delete();
        }
        
        $this->_redirect($this->_curModule);
    }

   
    
	
    
    
}