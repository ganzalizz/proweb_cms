<?php

/**
 * Layout Helper
 * вывод меню каталога товаров
 * 
 *
 */
class View_Helper_catalogMenu extends Zend_View_Helper_Abstract
{
	
	
	
	/**
	 * Блок с ножом на главной странице
	 *
	 * @return html
	 */
    public function catalogMenu($id_current = null)    {
    	$show_ids[]=$id_current;
    	$current_row = Catalog_Division::getInstance()->find($id_current)->current();
    	if ($current_row!=null){
	    	while ($parent=$current_row->getParent()) {
	    		$show_ids[] = $parent->id;
	    		$current_row = $parent;
	    	}  
    	}
        return Catalog_Division::getInstance()->getCatalogMenu(0, 0, $id_current, $show_ids);
    }
}
