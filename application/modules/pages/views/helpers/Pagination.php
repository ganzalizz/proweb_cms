<?php

/**
 * Layout Helper
 *
 */
class View_Helper_Pagination extends Zend_View_Helper_Abstract
{
   
/**
	 * @var Zend_View
	 */
	protected $_view = null ;
	
	/**
	 * Enter description here...
	 *
	 */
	public function init() {    
		$this->_view = Zend_Registry::get('view');
	}
	
	private $_count_links = 5;
	
	public function pagination($url, $total, $onpage=10, $current_page = 1){
             
		if ($total && $onpage){
                    
                
	       	$count_pages = ceil($total/$onpage);                
	       	$this->init();
	       	$this->_view->count_pages = $count_pages;
	       	
	       	$this->_view->url = $url;
	       	$this->_view->current = $current_page;
	       	$end = $this->_count_links;
	       	$first = 1;
	       	
	       	if ($count_pages > $this->_count_links && $current_page < $count_pages){
	       		$first = floor($current_page/$this->_count_links)*$this->_count_links;	       		
	       		$end = $first + $this->_count_links;
	       		$end = $end<$total ? $end : $total;
	       		
	       	} elseif ($current_page == $count_pages && $count_pages > $total){
	       		$first = $count_pages-$this->_count_links;
	       		$end=$count_pages;
	       	}
	       	$this->_view->end = $end;
	       	$this->_view->first = $first;
	       	
	       	return $this->_view->render( 'Pagination.phtml' ) ;
		}
		return '';
	}	
}