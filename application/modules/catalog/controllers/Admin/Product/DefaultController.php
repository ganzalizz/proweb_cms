<?php

/**
 * Catalog_Admin_Product_DefaultController
 *
 * @author Vitali
 * @version
 */

class Catalog_Admin_Product_DefaultController extends MainAdminController {

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
        $this->view->types = Catalog_Product_Default::getInstance()->getTypes();      
        $this->view->total = Catalog_Product_Default::getInstance()->fetchAll()->count();
        $this->view->all = Catalog_Product_Default::getInstance()->fetchAll(null, 'priority DESC' , $this->_onpage, $this->_offset);
      	 
       
    }
    
    /**
     * создание редактирование элемента
     */
    public function editAction(){
    	$id = $this->_getParam('id');
    	$is_new = 0;
    	if ($id){
    		$item = Catalog_Product_Default::getInstance()->find($id)->current();
    	} else {
    		$item = Catalog_Product_Default::getInstance()->fetchNew();
    		$is_new = 1;
    	}
    	
    	if ($this->getRequest()->isPost()){
    		if ($this->_getParam('title') && $this->_getParam('form_type')){	
	    		$data = $this->_getAllParams();
	    		$item->setFromArray(array_intersect_key($data, $item->toArray()));
	    		$item->save();
	    		if ($is_new){
	    			Catalog_Product_Default_Values::getInstance()->addNewValues($item);
	    		}
	    		$this->view->ok = 1;
    		} else {
    			$this->view->ok = 0;
    		}
    	}
    	$this->view->item = $item;
    	$this->view->types = Catalog_Product_Default::getInstance()->getTypes();
    }

  

    public function deleteAction() {
        $id = $this->_getParam('id',0);
        $item = Catalog_Product_Default::getInstance()->find($id)->current();
        if (!is_null($item)) {        	
        	Catalog_Product_Default_Values::getInstance()->deleteValues(null, $id);        	
            $item->delete();
        }
        
        $this->_redirect($this->_curModule);
    }

   
    
	
    
    
}