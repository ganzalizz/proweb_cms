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
		$this->view->addScriptPath(DIR_LIBRARY.'Ext/View/Scripts/');
		$ini = new Ext_Common_Config( 'news', 'backend' );
		$this->_basePicsPath = $ini->basePicsPath;
		$this->_onPage = $ini->countOnPage;
		
		$this->checkDirs();
		$this->view->layout()->title = "Новости";
		$this->view->lang = $lang = $this->_getParam( 'lang', 'ru' );
		$this->view->currentModul = $this->_curModule = SP . 'news' . SP . $lang . SP . $this->getRequest()->getControllerName();
		
	}
	
	public function indexAction() {		
		$this->view->layout()->action_title = "Список элементов";
		$page = $this->view->current_page = $this->_getParam( 'page', 1 );
		
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
		$paginator = News::getInstance()->getAll($this->_onPage, $page);
		$this->view->news = $paginator->getCurrentItems();
		$this->view->paginator = $paginator;		
		
	

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
		
		$id = $this->_getParam('id');
		if ($id){
			Form_FormNews::setRecordId($id);	
			$this->view->layout()->action_title = "Редактировать элемент";				
			$row = News::getInstance()->find( (int)$this->_getParam('id') )->current();	
					
		} else {
			$this->view->layout()->action_title = "Создать элемент";
			$row = News::getInstance()->fetchNew();
		}
		
		$form = new Form_FormNews();
    	
    	
			
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
	/**
	 * удаление элемента 
	 */
	public function deleteAction() {
		if ($this->_request->isXmlHttpRequest()){			
			$id = $this->_getParam('id');			
			$row = News::getInstance()->find($id)->current();
			if ($row!=null) {
				if ($row->small_img!=''){
					@unlink($this->_basePicsPath . 'small_img/'.$row->small_img);
				}
				if ($row->big_img!=''){
					@unlink($this->_basePicsPath . 'big_img/'.$row->big_img);
				}
				$row->delete();
				echo 'ok';
			} else {
				echo 'error';
			}
		}
		exit;
		
		
	
	}
	/**
	 * изменение активности
	 */
	public function changeactiveAction(){
		// проверка пришел ли запрос аяксом
		if ($this->_request->isXmlHttpRequest()){			
			$id = $this->_getParam('id');			
			$row = News::getInstance()->find($id)->current();
			if ($row!=null) {
				$row->is_active = abs($row->is_active-1);
				$row->save();
				echo '<img src="/img/admin/active_'.$row->is_active.'.png" />';
			} else {
				echo 'error';
			}
		}
		exit;
		
	}
	
	/**
	 * изменение отображение на главной
	 */
	public function changemainAction(){
		// проверка пришел ли запрос аяксом
		if ($this->_request->isXmlHttpRequest()){			
			$id = $this->_getParam('id');			
			$row = News::getInstance()->find($id)->current();
			if ($row!=null) {
				$row->is_main = abs($row->is_main-1);
				$row->save();
				echo '<img src="/img/admin/main_'.$row->is_main.'.gif" />';
			} else {
				echo 'error';
			}
		}
		exit;
		
	}
	/**
	 * установить снять флаг
	 * горячая новость
	 */
	public function changehotAction(){
		// проверка пришел ли запрос аяксом
		if ($this->_request->isXmlHttpRequest()){			
			$id = $this->_getParam('id');			
			$row = News::getInstance()->find($id)->current();
			if ($row!=null) {
				$row->is_hot = abs($row->is_hot-1);
				$row->save();
				echo '<img src="/img/admin/hot_'.$row->is_hot.'.png" />';
			} else {
				echo 'error';
			}
		}
		exit;
		
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
				$row = News::getInstance()->editNews($row, $form->getValidValues($this->_getAllParams()));				
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
		
		if (!$id){
			return $uploaded_images;
		}
		
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
	
	        
       public function inunAction()
       {
        $installed = $this->_getParam('installed');
        
        require_once 'NewsInstall.php';
        $install = new NewsInstall('news');
        
        if (($this->_request->isXmlHttpRequest()) && ($this->_getParam('installed')))
        {
            $install->Uninstall();
            $module = Modules::getInstance()->getModuleByName('news');
           
          echo  '													
		      <td class="main" >
			<strong>'.$module['name'].'</strong>
                      </td>
		      <td class="main" style="color:red;">'.
			$module['title'].' 
		      </td>											
		      <td class="main" >'.
			$module['describe'].'
		      </td>										
		      <td class="options">
			  <a id="'.$module['name'].'" href="#" onclick="return false;" title="Включить модуль"><img src="/img/admin/module_'.$module['installed'].'.png" alt="/img/admin/module_'.$module['installed'].'.png"></a>    
                      </td>'.
                     $this->view->ajaxStatusLink(array(
                                                  'target_id'	=>'row_'.$module['id'],
                                                  'link_id'	=>$module['name'],
                                                  'target_url'=>'/../../'.$module['name'].'/ru/admin_'.$module['name'].'/inun',
                                                  'url_data'	=>"{installed: ".$module['installed']."}",
                                                  'loader_img'=>"/img/horizontal_loader.gif"
                                                  ));
            
        }
        else
        {
            $install->Install();
            $module = Modules::getInstance()->getModuleByName('news');
           
          echo  '														
		      <td class="main" >
			<strong>'.$module['name'].'</strong>
                      </td>
		      <td class="main" " style="color:green;">'.
			$module['title'].' 
		      </td>											
		      <td class="main" >'.
			$module['describe'].'
		      </td>										
		      <td class="options">
			  <a id="'.$module['name'].'" href="#" onclick="return false;" title="Выключить модуль"><img src="/img/admin/module_'.$module['installed'].'.png" alt="/img/admin/module_'.$module['installed'].'.png"></a>    
                      </td>
		      '.$this->view->ajaxStatusLink(array(
                                                  'target_id'	=>'row_'.$module['id'],
                                                  'link_id'	=>$module['name'],
                                                  'target_url'=>'/../../'.$module['name'].'/ru/admin_'.$module['name'].'/inun',
                                                  'url_data'	=>"{installed: ".$module['installed']."}",
                                                  'loader_img'=>"/img/horizontal_loader.gif"
                                                  ));;
           
	} 
	
	exit;
		
    }

}