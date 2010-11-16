<?php

/**
 * Catalog_Admin_Product_ImagesController
 *
 * @author Vitali
 * @version
 */

class Catalog_Admin_Product_ImagesController extends MainAdminController {

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

    private $_owner = null;

    private $_id_product = null;

    public function init() {
        $this->initView();
        $this->layout = $this->view->layout();       
		$lang = $this->_getParam('lang','ru');
        $id_product = $this->_getParam('id_product',0);
        if ($id_product) {
            $this->_id_product = $id_product;
            $this->view->owner =$this->_owner =  Catalog_Product::getInstance()->find($id_product)->current();
            $this->view->id_product = $id_product;
        } else {
        	$this->_redirect("/catalog/$lang/admin_division");
        }
    
        $this->layout->title = "Каталог";
       
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
        $this->layout->action_title = "Список фотографий";
        if (!is_null($this->_owner)){
        	$this->layout->action_title.= " товара \"".$this->_owner->title.'"';
        }	
        
    	if ($this->_request->isPost()){
			$array_Priority = $this->_getParam('priority');
			$array_titles = $this->_getParam('title');
			Catalog_Product_Images::getInstance()->setProperties($array_Priority, $array_titles);			
			$action = $this->_getParam('operation');
			$ids = $this->_getParam('images');
			if ($action && $ids){
				Catalog_Product_Images::getInstance()->processImages($action, $ids);			
			}					
			Catalog_Product_Images::getInstance()->upload_Images($this->_id_product);
		}
		
        $id_product = $this->_getParam('id_product');        
        $this->view->total = Catalog_Product_Images::getInstance()->getImagesByProductId($id_product)->count();
        $this->view->all = Catalog_Product_Images::getInstance()->getImagesByProductId($id_product, $this->_onpage, $this->_offset);
      	 
        $this->view->options = Catalog_Product_Images::getInstance()->getOptions();
    }

  

    public function deleteAction() {
        $id = $this->_getParam('id',0);
        $item = Catalog_Product_Images::getInstance()->find($id)->current();
        if (!is_null($item)) {
            $item->delete();
        }
        $id_product = $this->_getParam('id_product', 0);
        $this->_redirect($this->_curModule.'/index/id_product/'.$id_product);
    }

    public function activateAction() {
        $id = $this->_getParam('id',0);
        $item = Catalog_Product_Images::getInstance()->changeActivity($id);        
        $id_product = $this->_getParam('id_product', 0);
        $this->_redirect($this->_curModule.'/index/id_product/'.$id_product);
    }

    public function mainAction() {
        $id = $this->_getParam('id',0);
        $item = Catalog_Product_Images::getInstance()->find($id)->current();
        if (!is_null($item)) {
            $item->main = abs($item->main-1);
            $item->save();  
        }
        $id_product = $this->_getParam('id_product', 0);
        $this->_redirect($this->_curModule.'/index/id_product/'.$id_product);
    }
    
	
    
    
}