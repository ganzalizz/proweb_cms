<?php
/**
 * Основной класс управления содержимым сайта
 *
 */
class Pages_Admin_PagesController extends MainAdminController {
    /**
     * @var string
     */
    private $_curModule = null;

    public function init() {
        $lang = $this->_getParam('lang', 'ru');
        $this->view->currentModule = $this->_curModule = SP.'pages'.SP.$lang.SP.$this->getRequest()->getControllerName();

    }


    /**
     * Список всех страниц
     *
     */

    public function indexAction() {        
        $lang = $this->_hasParam('lang') ? $this->_getParam('lang') : 'ru';
       
        $root = Pages::getInstance()->getRoot();
        //print_r($root->toArray()); exit;
        $tree = Pages::getInstance()->getTree($root->id_parent);
        $this->view->html_tree = $tree;
        

        if(!empty($tree)) {
           

            $this->view->pageName = 'Модуль управления содержимым сайта';
//			$this->view->help = 'Модуль управления содержимым сайта';
            $this->view->lang = $lang;
        }
       
        
        
    }

    public function moveAction() {
        if($this->_hasParam('node_id') && $this->_hasParam('target_id') && $this->_hasParam('type')) {
            $node = (int)$this->_getParam('node_id');
            $target = (int)$this->_getParam('target_id');
            $point = (string)$this->_getParam('type');
            $pages = Pages::getInstance();
            $pages->replace($node, $target, $point);
            echo 'ok';
        } else {
            echo 'err';
        }
        exit();
    }


    /**
	 * Добавление новой страницы
	 *
	 */
	public function addAction(){
		$lang = $this->getParam('lang');

		if(!$this->_hasParam('parent_id')){
			$this->_redirect($this->_curModule);
		}

		$page = Pages::getInstance()->createRow();
		$options = PagesOptions::getInstance()->createRow();


		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getParams();

			$ok = 1;
			$err = array();
			if (trim($data['name'])==''){
				$err[] = 'Незаполнено поле название';
				$ok = 0;
			}
			if (!Pages::getInstance()->checkPath($data['path'], null, $lang)){
				$err[] = "'{$data['path']}' такой адрес страницы уже занят";
				$ok = 0;
			}
			if ($ok){

				$id = Pages::getInstance()->addPage($data);
				$page = Pages::getInstance()->find($id)->current();
				$img_name = $_FILES['image']['name'];
				$img_source = $_FILES['image']['tmp_name'];
				$delete_img = $this->_getParam('delete_img');
				if ($img_name!='' && $img_source!='' && !$delete_img){
					$ext = @end(explode('.', $img_name));
					$img = DIR_PUBLIC.'pics/default/'.$id.'_img.'.$ext;
					if (copy($img_source,$img )){
						$page->img = $id.'_img.'.$ext;

						$page->save();
					}
					/*if($this->img_resize($img_source, $img, $width = 38, $heiht = 38 )){
						$page->img = $id.'_img.'.$ext;
						$page->save();
					}	*/
				} else if ($delete_img){
					@unlink(DIR_PUBLIC.'pics/default/'.$item->img);
					$page->img='';
					$page->save();
				}
				$data['id'] = $id;
				$this->addRoute($data);

				$this->_redirect($this->_curModule);
			}else{
				$this->view->err = $err;
			}


		}
		$this->view->page = $page;
		$this->view->options = $options;
		$fck = $this->getFck('content', '90%', '400');
		$this->view->fck = $fck;
		$this->view->introText = $this->getFck('intro', '90%', '150','Basic');
		$parentId = (int)$this->getRequest()->getParam('parent_id');
		$this->view->parentId = $parentId;
		$this->view->menu = MenuTypes::getInstance()->fetchAll();
        
		$this->view->pageName = 'Добавить страницу';
		$modules_types = Modules::getInstance()->fetchAll('active=1','priority DESC');
		if (count($modules_types)){
                    foreach ($modules_types as $type){
                        $div_types = SiteDivisionsType::getInstance()->fetchAll('active=1 AND'." `module` = '".$type->name."'", 'priority DESC');
                        foreach ($div_types as $div){
                            if(count($modules_types)>1){
                            $types_select[$type->title][$div->id]=$div->title;
                            }
                            else{
                            $types_select[$div->id]=$div->title;
                            }
                        }
                    }
                }

                $this->view->divisions_type = $types_select;
		$this->view->lang = $lang;

	}

    /**
     * Удаление страницы
     *
     */
    public function deleteAction() {
        if($this->_hasParam('id')) {
            $id = (int)$this->getRequest()->getParam('id');
            Pages::getInstance()->remove($id);
        }
        if ($this->_request->isXmlHttpRequest()) {
            echo 'ok';
            exit;
        }
        
        $this->_redirect($this->_curModule);
    }

    public function pubAction() {
        if($this->_hasParam('id')) {
            $id = (int)$this->getRequest()->getParam('id');
            Pages::getInstance()->changeActive($id);
          
        	if ($this->_request->isXmlHttpRequest()) {
            	echo 'ok';
                exit;
            }
         

        }
        $this->_redirect($this->_curModule);
    }



    public function editAction() {
        $lang = $this->getParam('lang');
        $id = (int)$this->getRequest()->getParam('id');


        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getParams();

            $ok = 1;
            $err = array();
            if (trim($data['title'])=='') {
                $err[] = 'Незаполнено поле название';
                $ok = 0;
            }
            if (!Pages::getInstance()->checkPath($data['path'], $id, $lang)) {
                $err[] = "'{$data['path']}' такой адрес страницы уже занят";
                $ok = 0;
            }

            Pages::getInstance()->editPage($data);
            $error = $this->editRoute($data);
            $page = Pages::getInstance()->find($id)->current();
            $img_name = $_FILES['image']['name'];
            $img_source = $_FILES['image']['tmp_name'];
            $delete_img = $this->_getParam('delete_img');
            if ($img_name!='' && $img_source!='' && !$delete_img) {
                $ext = @end(explode('.', $img_name));
                $img = DIR_PUBLIC.'pics/default/'.$id.'_img.'.$ext;
                if (copy($img_source,$img )) {
                    $page->img = $id.'_img.'.$ext;
                    $page->save();
                }

            } else if ($delete_img) {
                @unlink(DIR_PUBLIC.'pics/default/'.$item->img);
                $page->img='';
                $page->save();
            }
            if ($ok) {
                $this->view->ok = 1;
            }else {
                $this->view->err = $err;
            }
        }
        $this->view->page = $page = Pages::getInstance()->getPage($id);

        $this->view->options = PagesOptions::getInstance()->getPageOptions($id);


        $this->view->menu = MenuTypes::getInstance()->getAll();
        $this->view->pageMenu = Menu::getInstance()->getMenuPage($id);


        $fck = $this->getFck('content', '90%', '400');
        $this->view->fck = $fck;
        $this->view->introText = $this->getFck('introText', '90%', '150','Basic');
        $this->view->pageName = 'Редактировать страницу';
        if ($page->allow_delete==1) {
            $div_types = SiteDivisionsType::getInstance()->fetchAll('active=1','priority DESC');
        } else $div_types =SiteDivisionsType::getInstance()->fetchAll('id='.(int)$page->id_div_type);


        $types_select = array();
        if (count($div_types)) {
            foreach ($div_types as $type) {
                $types_select[$type->id] = $type->title;
            }
        }
        $this->view->divisions_type = $types_select;
        $this->view->lang = $lang;
    }

   
    

    public function gotomoduleAction() {
        $id_page = $this->_getParam('id_page');
        $id_type = $this->_getParam('id_type');
        $lang = $this->_getParam('lang', 'ru');
        if ($id_page && $id_type) {
            $type_row = SiteDivisionsType::getInstance()->find($id_type)->current();
            if ($type_row!=null && $type_row->module!='' && $type_row->controller_backend!='' && $type_row->action_backend!='') {
                $url = "/$type_row->module/$lang/$type_row->controller_backend/$type_row->action_backend/id_page/$id_page/";
                $this->_redirect($url);
            } else $this->_redirect($this->_curModule);

        }

    }


    /**
     * редактирование роута для страницы/раздела
     *
     * @param array $data
     * @return bool
     */
    private function editRoute($data) {
        //Loader::loadCommon('Router');
        if ($data['id_div_type']) {
            $division_type = SiteDivisionsType::getInstance()->find($data['id_div_type'])->current();
            if ($division_type!=null) {
                $module = $division_type->module;
                $controller = $division_type->controller_frontend;
                $action = $division_type->action_frontend;
                Router::getInstance()->replaceRoute($data, $action, $controller, $module);
                return true;
            }
        }
    }

    /**
     * редактирование роута для страницы/раздела
     *
     * @param array $data
     * @return bool
     */
    private function addRoute($data) {
        //Loader::loadCommon('Router');
        if ($data['id_div_type']) {
            $division_type = SiteDivisionsType::getInstance()->find($data['id_div_type'])->current();
            if ($division_type!=null) {
                $module = $division_type->module;
                $controller = $division_type->controller_frontend;
                $action = $division_type->action_frontend;
                if(!Router::getInstance()->addRoute($data, $action, $controller, $module)) {
                    $error = "Такой URL уже существует!";
                }
                return true;
            }
        }
    }
    
}
