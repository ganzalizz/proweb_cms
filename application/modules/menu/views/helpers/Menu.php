<?php

/**
 * Layout Helper
 *
 */
class View_Helper_Menu extends Zend_View_Helper_Abstract {

    /**
     * @var Zend_View
     */
    protected $_view = null ;

    /**
     * Enter description here...
     *
     */
    public function init() {

        $this->_view = Zend_Registry::get('view') ;

    }

    /**
     * Site menu
     *
     * @param string $type
     * @param string $version
     * @return phtml
     */
    public function Menu($type, $version, $current=0) {
        $this->init();

        $menu_rowset = Menu::getInstance()->getMenu($type);
        $result = array();
        $infor = array();
        
        $menu = array();
        
        
        if ($menu_rowset!=null){
        	// формируем первый уровень меню
        	foreach ($menu_rowset as $key=> $item){
        		if ($item->level ==1){
        			$menu[$item->id]['item'] = $item;
        			unset($menu_rowset[$key]);
        		}
        	}
        	// формируем второй уровень меню
        	foreach ($menu_rowset as $key=>$second_level_item){
        		if ($second_level_item->level == 2){
        			$menu[$second_level_item->parentId]['childs'][] = $second_level_item;
        			unset($menu_rowset[$key]);
        		}
        	}
        	
        }
        
      // print_r($menu); exit;
        
       /* foreach ($menus as  $data) {
            if($data['level'] == 1) {
                $result[$data['pageId']] = $this->getPage($data['pageId'], $version);

                $infor[$data['pageId']] = array();
            }
            elseif($data['level'] == 2) {
                $infor[$data['parentId']][] = $this->getPage($data['pageId'], $version);
            }
        }*/

        $this->_view->lang = $version;
        /*$this->_view->menu = array(
                'level1' => $result,
                'level2' => $infor
        );*/
        $this->_view->menu = $menu;
        
        $this->_view->current = $current;
        /* Изменил для простоты в дальнейшем при создании меню из CMS надо сразу генерировать файл шаблона с названием идентификатора меню
         * так мне кажется будет правильнее и не будет сложностей с if
         */
        return $this->_view->render($type.'.phtml');
        //if ($type == 'horizontal_menu') return $this->_view->render('horizontal_menu.phtml');
        //else if ($type == 'vertical_menu') return $this->_view->render('vertical_menu.phtml');
    }



    private function getPage($id, $version) {
        $page = Pages::getInstance()->getPage($id);
        if ($page->version!='ru') {
            $page->path = $page->version.'/'.$page->path;
        }
        return $page;
    }

} 