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
    
    private $_basePicsPath = null;

    public function init() {
        $lang = $this->_getParam('lang', 'ru');
        $this->view->currentModule = $this->_curModule = SP.'pages'.SP.$lang.SP.$this->getRequest()->getControllerName();
        $this->view->addScriptPath(DIR_LIBRARY.'Ext/View/Scripts/');
		$ini = new Ext_Common_Config( $this->getRequest()->getModuleName(), 'backend' );
		$this->_basePicsPath = $ini->basePicsPath;
		$this->view->layout()->title = $ini->module->name;
		$this->checkDirs();

    }


    /**
     * Список всех страниц
     *
     */
    public function indexAction() {        
        $lang = $this->_hasParam('lang') ? $this->_getParam('lang') : 'ru';       
        $root = Pages::getInstance()->getRoot();       
        $tree = Pages::getInstance()->getTree($root->id_parent);
        $this->view->html_tree = $tree;
        $this->view->lang = $lang;

        
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
        $div_types = array();
        if ($id){
        	$page = Pages::getInstance()->getPage($id);
        	Form_PagesForm::setMenuValues(Menu::getInstance()->getMenuPage($page->id));        	
        	Form_PagesForm::setRecordId($id);
        	Form_PagesForm::setdiv_typeValue($page->id_div_type);
        	if ($page->allow_delete==1) {
            	$div_types = SiteDivisionsType::getInstance()->getAllActive();
        	} else {
        		$div_types =SiteDivisionsType::getInstance()->getOne($page->id_div_type);	
        	}
        } else {
        	$page = Pages::getInstance()->fetchNew();
        	$div_types = SiteDivisionsType::getInstance()->getAllActive();
        }
        
    	
        Form_PagesForm::setdiv_typeOptions($div_types);       
        Form_PagesForm::setMenuOptions(MenuTypes::getInstance()->getAllAsArray());
       
       
		$form = new Form_PagesForm();
		
		if ($this->_hasParam('id_parent')){
			$form->getElement('id_parent')->setValue($this->_getParam('id_parent'));
		}
		
		
		
		if ($this->_request->isPost()){
			$data = $this->processForm($form, $page);
			$form->populate($data);
		
		} else {
			$form->populate($page->toArray());
			
			$options = PagesOptions::getInstance()->getPageOptions($id)->toArray();			
	        $options['page_title'] = $options['title'];
	        unset($options['title']);	        
	        $form->populate($options);
		}
		
   		if ($page->img){
			$form->getElement('img')->setAttrib('img', '/pics/pages/thumbs/'.$page->img);
   		}	
	
      
        
      
        
       
        $this->view->lang = $lang;
        $this->view->form = $form;
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
	 * Проверка существования директорий для картинок
	 * и создание их если нет
	 * @param string $this->_basePicsPath
	 */
	private function checkDirs() {
		//TODO: вынести директории в массив
		if (! is_dir( $this->_basePicsPath . '/img' )) {
			mkdir( $this->_basePicsPath . '/img', 0777, true );
		}		
		if (! is_dir( $this->_basePicsPath . '/thumbs' )) {
			mkdir( $this->_basePicsPath . '/thumbs', 0777, true );
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
    
/**
	 * 
	 * @param Ext_Form $form
	 * @param Zend_Db_Table_Row $row
	 * @return Array
	 */
	private function processForm($form, $row) {
		
		if ($this->_request->isPost()) {
			// добавление записи в базу
			if ($form->isValid( $this->_getAllParams() ) ) {
				
				if ($row->id==''){
					// создание записи
					$data = $form->getValidValues($form->getValues());
					$row = Pages::getInstance()->addPage($data, $row);
					$this->addRoute($data);
				} else {
					// редактирование записи
					$data = $form->getValidValues($form->getValues());
					$row = Pages::getInstance()->editPage($data, $row);
					$this->editRoute($form->getValues());		
				}

				if (!is_null($row) && $row->id){ // запись в базе создана загружаем картинки					
					$aploaded_images = $this->reciveFiles( $row->id );
					if (isset($aploaded_images['img']) && !$this->_getParam('img_delete')){
						
						$thumb = Ext_Common_PhpThumbFactory::create($aploaded_images['img']);
						$thumb->setOptions(array('jpegQuality'=>95));                        
						$thumb->resize(100);					
						$thumb->save($this->_basePicsPath.'thumbs/'.basename($aploaded_images['img']));
						
						if ($row->img!='' && $row->img != basename($aploaded_images['img'])){
							@unlink($this->_basePicsPath.'img/'.$row->img);
							@unlink($this->_basePicsPath.'thumbs/'.$row->img);						
						}
						
						$row->img = basename($aploaded_images['img']);
						
					} elseif ($this->_getParam('img_delete')){					
						@unlink($this->_basePicsPath.'img/'.$row->img);
						@unlink($this->_basePicsPath.'thumbs/'.$row->img);
						$row->img = '';
					}
					
					
					$row->save();
					$form->getElement('id')->setValue($row->id);
					$this->view->ok = 1;				
				}
				
			   
			} 			
			return $form->getValues();
		}
	}
	/**
	 * 
	 * @param unknown_type $destination
	 */
	private function reciveFiles($id) {
		$upload = new Zend_File_Transfer_Adapter_Http( );
		
		$uploaded_images = array();
		
		if (!$id){
			return $uploaded_images;
		}
		
		// загрузка маленькой картинки
		if ($upload->getFileInfo('img')){		
			$img_name = $this->_basePicsPath . 'img/' . $id . '_' .
				 basename( $upload->getFileName( 'img' ) ); 
			$upload->addFilter( 
				'Rename',
				array(
					'target'=>$img_name,
					'overwrite'=>true
				),
				'img'
			);
			if ($upload->receive( 'img' )){
				$uploaded_images['img'] = $img_name; 
			}
		}
		
		
		return $uploaded_images;
	
	}
    
}
