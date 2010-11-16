<?php
class Catalog_Admin_DivisionController extends MainAdminController 
{
	/**
	 * Controller name
	 *
	 * @var string
	 */
	private  $_current_module =null;	
	
	public function init()
	{	
		$this->initView();
		$this->layout = $this->view->layout();
		$this->layout->title = "Каталог";			
		$this->layout->action_title = 'Дерево разделов';				
		$lang = $this->_getParam('lang','ru');
		$this->view->currentModule = $this->_current_module = SP.$this->getRequest()->getModuleName().SP.$lang.SP.$this->getRequest()->getControllerName();
		
	}
		
	
	
	public function indexAction()
	{
		$this->view->lang = $lang = $this->getParam('lang','ru');		
		$tree = Catalog_Division::getInstance()->getTree();
		$this->view->html_tree = $tree;
	}
	
	

	
	public function editAction(){
		
		$id = $this->_getParam('id');		
		if ($id){
			$item = Catalog_Division::getInstance()->find($id)->current();
			$this->layout->action_title = 'Редактировать раздел';
		} else {
			$item = Catalog_Division::getInstance()->createRow();
			$this->layout->action_title = 'Создать раздел';
		}	
		$parent_id = $this->_getParam('parent_id');
				
		$errors = array();
		$ok = 1;
		if ($this->_request->isPost()){
			$data = $this->_getAllParams();
			unset($data['id']);
			if ($parent_id){
				$parent = Catalog_Division::getInstance()->find($parent_id)->current();
				
				
				if ($parent!=null){
					$data['level'] = $parent->level+1;
					$data['parent_id'] = $parent_id;					
				}
			}
			
			if ($data['name']!=''){		
				$sort = Catalog_Division::getInstance()->getMaxSort((int)$parent_id);
				$data['sortid'] = (int)$sort;					
				$item->setFromArray(array_intersect_key($data, $item->toArray()));
				$id = $item->save();					
				$img_name = $_FILES['img']['name'];
				$img_source = $_FILES['img']['tmp_name'];
				$delete_img = $this->_getParam('delete_img');				
				if ($img_name!='' && $img_source!='' && !$delete_img){
					$ext = @end(explode('.', $img_name));
					$small_img = DIR_PUBLIC.'pics/catalog/division/'.$id.'_img.'.$ext;								
					if($this->img_resize($img_source,$small_img, 130, 115 )){
						$item->img = $id.'_img.'.$ext;
						$item->save();
					}
					
				} else if ($delete_img){
					@unlink(DIR_PUBLIC.'pics/catalog/division/'.$item->img);					
					$item->img='';					
					$item->save();
				}		
				$this->view->ok = $ok;
			} else{				
				$ok = 0;
				$errors[]='Незаполнено поле название';
			} 
		}
		
		$fck1 = $this->getFck('intro', '90%', '150','Basic');	
		$this->view->intro = $fck1;
		$fck2 = $this->getFck('description', '90%', '300');
		$this->view->fck_content = $fck2;
		$this->view->item =$item;
	}
	
	
	
	public function deleteAction(){
		if($this->_hasParam('id')){
			$id = (int)$this->getRequest()->getParam('id');
			$division_row = Catalog_Division::getInstance()->find($id)->current();
			if (null != $division_row) {				
				$division_row->delete();				
					echo 'ok'; 	
			}
		}
		if ($this->_request->isXmlHttpRequest()){
			exit;
		}
		$this->_redirect($this->_current_module);
	}
	
	
	
	
	
	
	
	
	

    public function copyAction(){
//		print (var_dump($this->_request->getParams()));exit;
		if($this->_hasParam('id')){
			Catalog_Division::getInstance()->copyDivisionWithChildren($this->_getParam('id'));
		}

//		exit;
//		$lang = $this->_hasParam('lang') ? $this->_getParam('lang') : 'ru';
		$this->_redirect('/admin/ru/Catalog_Division/index/id_page/191/');
	}
	
	public function moveAction(){
		if($this->_hasParam('node_id') && $this->_hasParam('target_id') && $this->_hasParam('type')){			
			$node = (int)$this->_getParam('node_id');
			$target = (int)$this->_getParam('target_id');
			$point = (string)$this->_getParam('type');
			$model = Catalog_Division::getInstance();
			$model->replace($node, $target, $point);
			echo 'ok';
		} else {
			echo 'err';
		}
		exit();
	}
}
