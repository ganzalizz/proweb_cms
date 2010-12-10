<?php

class News_Admin_NewsController extends MainAdminController {
	/**
	 *
	 * @var int
	 */
	private $_id_page = null;
	/**
	 * Pages_row
	 * @var object
	 */
	private $_owner = null;
	
	private $_curModule = null;
	
	private $_basePicsPath = null;
	
	private $_onPage = null;
	
	public function init() {
		$this->view->addHelperPath( Zend_Registry::get( 'helpersPaths' ), 'View_Helper' );
		$ini = new Ext_Common_Config( 'news', 'backend' );
		$this->_basePicsPath = $ini->basePicsPath;
		$this->_onPage = $ini->countOnPage;
		
		$this->checkDirs();
		
		$this->view->lang = $lang = $this->_getParam( 'lang', 'ru' );
		$this->view->currentModul = $this->_curModule = SP . 'news' . SP . $lang . SP . $this->getRequest()->getControllerName();
	}
	
	public function indexAction() {
		$this->view->layout()->action_title = "Список элементов";
		$page = $this->view->current_page = $this->_getParam( 'page', 1 );
		$onPage = $this->view->onpage = 50;
		$this->view->counter = ($page - 1) * $onPage;
		$this->view->news = News::getInstance()->fetchAll();
		// $this->view->total = News::getInstance()->getCount("id_page='".$this->_id_page."'");
	

	}
	
	public function addAction() {
		$this->view->layout()->action_title = "Создать элемент";		
		
		$form = new Form_FormNews( );
		
		if ($this->_request->isPost()){
			
		}
		
		$this->processForm( $form, $this->getRequest() );

		$this->view->form = $form;
	}
	
	public function editAction() {
		$form = new Form_FormNews();
		$id = $this->_getParam('id');
		if ($id){
			$this->view->layout()->action_title = "Редактировать элемент";				
			$row = News::getInstance()->find( (int)$this->_getParam('id') )->current();				
		} else {
			$this->view->layout()->action_title = "Создать элемент";
			$row = News::getInstance()->fetchNew();
		}
			
		if (!is_null($row)){
			
			if ($this->_request->isPost()){
			  	$row = $this->processForm( $form, $row );
			}
			$form->populate($row->toArray());
			if ($row->small_img){
				$form->getElement('small_img')->setAttrib('small_img', '/pics/news/thumbs/'.$row->small_img);
			}
			if ($row->big_img){
				$form->getElement('big_img')->setAttrib('big_img', '/pics/news/big_img/'.$row->big_img);
			}
			if (!$id){				
				// по умолчанию создаем активный элемент 
				$form->getElement('is_active')->setChecked(true);
			}	
				
		}
		
		
		
		
		$this->view->form = $form;
		
	// $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
	

	}
	
	public function checkurlAction() {
		$url = $this->_getParam( 'url', '' );
		$id = $this->_getParam( 'id', '' );
		if ($url) {
			$item = News::getInstance()->fetchRow( "url='$url' AND id!='$id'" );
			if ($item != null) {
				echo 'err';
			} else
				echo 'ok';
		
		}
		exit();
	}
	
	public function deleteAction() {
		$lang = $this->getRequest()->getParam( 'lang', 'ru' );
		$curModul = SP . 'news' . SP . $lang . SP . $this->getRequest()->getControllerName();
		if (! $this->_hasParam( 'id' )) {
			$this->_redirect( $curModul . '/index/id_page/' . $this->_id_page );
		} else {
			$id = ( int ) $this->getRequest()->getParam( 'id' );
			
			$novost = News::getInstance()->getNewById( $id );
			
			News::getInstance()->deleteNew( $id );
			$sortSession = new Zend_Session_Namespace( 'sortSearch' );
			$pageSession = new Zend_Session_Namespace( 'pageSearch' );
			
			if (isset( $pageSession->page )) {
				$page_link = "/page/" . $pageSession->page;
			} else
				$page_link = "";
			
			if (isset( $sortSession->sort )) {
				$sort_link = "/sort/" . strtolower( $sortSession->sort );
			} else
				$sort_link = "";
			
			$this->_redirect( $curModul . '/index/id_page/' . $this->_id_page );
		}
	
	}
	
	public function setmainAction() {
		$lang = $this->getRequest()->getParam( 'lang', 'ru' );
		$curModul = SP . 'news' . SP . $lang . SP . $this->getRequest()->getControllerName();
		if (! $this->_hasParam( 'id' )) {
			$this->_redirect( $curModul . '/index/id_page/' . $this->_id_page );
		} else {
			$id = ( int ) $this->getRequest()->getParam( 'id' );
			News::getInstance()->setMainNew( $id );
			$sortSession = new Zend_Session_Namespace( 'sortSearch' );
			$pageSession = new Zend_Session_Namespace( 'pageSearch' );
			
			if (isset( $pageSession->page )) {
				$page_link = "/page/" . $pageSession->page;
			} else
				$page_link = "";
			
			if (isset( $sortSession->sort )) {
				$sort_link = "/sort/" . strtolower( $sortSession->sort );
			} else
				$sort_link = "";
			
			$this->_redirect( $curModul . '/index/id_page/' . $this->_id_page );
		}
	}
	
	public function unsetmainAction() {
		$lang = $this->getRequest()->getParam( 'lang', 'ru' );
		$curModul = SP . 'news' . SP . $lang . SP . $this->getRequest()->getControllerName();
		if (! $this->_hasParam( 'id' )) {
			$this->_redirect( $curModul . '/index/id_page/' . $this->_id_page );
		} else {
			$id = ( int ) $this->getRequest()->getParam( 'id' );
			News::getInstance()->unsetMainNew( $id );
			$sortSession = new Zend_Session_Namespace( 'sortSearch' );
			$pageSession = new Zend_Session_Namespace( 'pageSearch' );
			
			if (isset( $pageSession->page )) {
				$page_link = "/page/" . $pageSession->page;
			} else
				$page_link = "";
			
			if (isset( $sortSession->sort )) {
				$sort_link = "/sort/" . strtolower( $sortSession->sort );
			} else
				$sort_link = "";
			
			$this->_redirect( $curModul . '/index/id_page/' . $this->_id_page );
		}
	}
	
	public function activateAction() {
		if ($this->_hasParam( 'id' )) {
			$id = ( int ) $this->getRequest()->getParam( 'id' );
			Pages::getInstance()->pubPage( $id );
		}
		$this->view->lang = $this->_getParam( 'lang' );
		$lang = $this->_hasParam( 'lang' ) ? $this->getParam( 'lang' ) : 'ru';
		$this->_redirect( "/news/$lang/admin_news/" );
	}
	
	public function unactivateAction() {
		if ($this->_hasParam( 'id' )) {
			$id = ( int ) $this->getRequest()->getParam( 'id' );
			Pages::getInstance()->unpubPage( $id );
		}
		$lang = $this->_getParam( 'lang' );
		$this->view->lang = $lang;
		$this->_redirect( "/news/$lang/admin_news/" );
	}
	
	/**
	 * 
	 * @param Ext_Form $form
	 * @param Zend_Controller_Request_Http $request
	 */
	private function processForm($form, $row) {
		
		
		
		if ($this->_request->isPost()) {
			// добавление записи в базу
			if ($form->isValid( $this->_getAllParams() ) && $row->id=='') {
				$row = News::getInstance()->addNews($row,  $form->getValidValues($form->getValues()) );
				
			   // редактирование записи
			} elseif ($form->isValid( $this->_getAllParams() ) && $row->id > 0) {				
				$row = News::getInstance()->editNews($row, $form->getValidValues($form->getValues()));				
			}
			
			
			
			
			if (!is_null($row)){ // запись в базе создана загружаем картинки					
				$aploaded_images = $this->reciveFiles( $row->id );
				if (isset($aploaded_images['small_img']) && !$this->_getParam('small_img_delete')){
					
					$thumb = Ext_Common_PhpThumbFactory::create($aploaded_images['small_img']);
					$thumb->setOptions(array('jpegQuality'=>95));                        
					$thumb->resize(100);					
					$thumb->save($this->_basePicsPath.'thumbs/'.basename($aploaded_images['small_img']));
					
					if ($row->small_img!='' && $row->small_img != basename($aploaded_images['small_img'])){
						@unlink($this->_basePicsPath.'small_img/'.$row->small_img);
						@unlink($this->_basePicsPath.'thumbs/'.$row->small_img);						
					}
					
					$row->small_img = basename($aploaded_images['small_img']);
					
				} elseif ($this->_getParam('small_img_delete')){					
					@unlink($this->_basePicsPath.'small_img/'.$row->small_img);
					@unlink($this->_basePicsPath.'thumbs/'.$row->small_img);
					$row->small_img = '';
				}
				if (isset($aploaded_images['big_img']) && !$this->_getParam('big_img_delete')){
					
					if ($row->big_img!='' && $row->big_img != basename($aploaded_images['big_img'])){
						@unlink($this->_basePicsPath.'big_img/'.$row->big_img);						
					}										
					$row->big_img = basename($aploaded_images['big_img']);
					
				} elseif ($this->_getParam('big_img_delete')) {
					@unlink($this->_basePicsPath.'big_img/'.$row->big_img);	
					$row->big_img = '';
				}
				
				$row->save();
				$this->view->ok = 1;
				return $row;
			}
		}
	}
	/**
	 * 
	 * @param unknown_type $destination
	 */
	private function reciveFiles($id) {
		$upload = new Zend_File_Transfer_Adapter_Http( );
		
		$uploaded_images = array();
		
		
		
		// загрузка маленькой картинки
		if ($upload->getFileInfo('small_img')){		
			$small_img_name = $this->_basePicsPath . 'small_img/' . $id . '_' .
				 basename( $upload->getFileName( 'small_img' ) ); 
			$upload->addFilter( 
				'Rename',
				array(
					'target'=>$small_img_name,
					'overwrite'=>true
				),
				'small_img'
			);
			if ($upload->receive( 'small_img' )){
				$uploaded_images['small_img'] = $small_img_name; 
			}
		}
		// загрузка большой картинки
		if ($upload->getFileInfo('big_img')){
			$big_img_name = $this->_basePicsPath . 'big_img/' . $id . '_' . basename( $upload->getFileName( 'big_img' ) ); 
			$upload->addFilter( 
				'Rename', 
				array(
					'target'=>$big_img_name,
					'overwrite'=>true
				),
				'big_img'				
			);		
			if ($upload->receive( 'big_img' )){
				$uploaded_images['big_img'] = $big_img_name;
			}
		}	
		
		return $uploaded_images;
	
	}
	
	
	
	/**
	 * Проверка существования директорий для картинок
	 * и создание их если нет
	 * @param string $this->_basePicsPath
	 */
	private function checkDirs() {
		//TODO: вынести директории в массив
		if (! is_dir( $this->_basePicsPath . '/small_img' )) {
			mkdir( $this->_basePicsPath . '/small_img', 0777, true );
		}
		if (! is_dir( $this->_basePicsPath . '/big_img' )) {
			mkdir( $this->_basePicsPath . '/big_img', 0777, true );
		}
		if (! is_dir( $this->_basePicsPath . '/thumbs' )) {
			mkdir( $this->_basePicsPath . '/thumbs', 0777, true );
		}
	}
	
	public function installAction() {
		//        $view = Zend_Layout::getMvcInstance()->getView();
		//        $view->jQuery->addJavascriptFile('/js/jquery-1.4.2.min')
		//                     ->addJavascriptFile('/js/jquery-ui-1.8.6.custom.min.js');
		require_once 'NewsInstall.php';
		echo 'Begin';
		$install = new News_Admin_NewsInstall( 'news' );
		// $install->Uninstall();
		$install->Install();
	
	}

}