<?php

/**
 * IndexController - The default controller class
 * 
 * @author
 * @version 
 */

class News_IndexController extends Zend_Controller_Action {

	/**
	 * Инициализирует класс
	 */
	public function init() {
	}

	/**
	 * 
	 */
    public function indexAction() {
		$this->view->lang = $this->_getParam('lang', 'ru')	;	
		$this->view->options = PagesOptions::getInstance()->getPageOptions($this->_getParam('id'));
		$page = Pages::getInstance()->getPage($this->_getParam('id'));
		$this->setTemplate($page->template);
		$this->view->page = $page;
		$url = $this->_getParam('item', '');
		if ($url){
			$this->view->novost = $item = News::getInstance()->fetchRow(" url='$url' AND pub=1");
			$options = PagesOptions::getInstance()->fetchRow("type='news' AND item_id=".(int)$item->id);
				if ($options!=null && ($options->title!='' || $options->descriptions!='' || $options->keywords!='')){
					$this->view->options = $options;
				}
		}
		else {
		$ipage = $this->view->current_page = $this->_getParam('page', 1);
		$onpage = $this->view->onpage = 15;	
		$where = "pub=1 AND page_id=".(int)$page->id;
		$user_id = $this->_getParam('user', '');
		if ($user_id){
			$where.=' AND peoples_id='.(int)$user_id;
			$this->view->user_id = $user_id;
			$user = Peoples::getInstance()->find($user_id)->current();
			$this->view->user_name = $user->name;
		}
		$news = News::getInstance()->fetchAll($where, 'created_at DESC', $onpage,  ($ipage-1)*$onpage);			
		$count = $this->view->total =  sizeof(News::getInstance()->fetchAll($where));
		$convert_news =array();		
		foreach ($news as $data){
			$data->created_at=News::getInstance()->convertData($data->id);			
			$convert_news[]=$data;
		}
    	
		$news=null;
		$this->view->news = $convert_news;
		}
    	//$this->setTemplate($page->template);
    }
    
    /**
     * 
     */
    
    private function setTemplate($template){
		
		$layoutManager = $this->getHelper('LayoutManager');
		$layoutManager->useLayoutName($template);
		//$layoutManager->getLayout($template)->addRequest(new Zend_Layout_Request('header', 'header', 'template'));
		//$layoutManager->getLayout($template)->addRequest(new Zend_Layout_Request('cart', 'preview', 'cart', 'catalog'));
		$layoutManager->getLayout($template)->addRequest(new Zend_Layout_Request('headerdefault', 'headerdefault', 'template'));
		//$layoutManager->getLayout($template)->addRequest(new Zend_Layout_Request('inner', 'inner', 'template'));		
		//$layoutManager->getLayout($template)->addRequest(new Zend_Layout_Request('right', 'right', 'template'));		
		$layoutManager->getLayout($template)->addRequest(new Zend_Layout_Request('footer', 'footer', 'template'));
		//$layoutManager->getLayout($template)->addRequest(new Zend_Layout_Request('footerDefault', 'footerDefault', 'template'));
		$layoutManager->getLayout($template)->addRequest(new Zend_Layout_Request('leftcollum', 'leftcollum', 'template'));
	}
    
}