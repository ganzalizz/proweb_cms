<?php

/**
 * Admin_ModulesController
 * 
 * @author Grover
 * @version 1.0
 */

class Admin_ModulesController extends MainAdminController  {
	
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
	
	public function init(){
		//$this->initView();
		$this->layout = $this->view->layout() ;
		$this->layout->title = "Управление модулями";			
		$this->view->caption = 'Список модулей';			
		$lang = $this->_getParam('lang','ru');		
		$this->view->currentModule = $this->_curModule = SP.'admin'.SP.$lang.SP.$this->getRequest()->getControllerName();
		$this->_page = $this->_getParam('page', 1);	
		$this->_offset =($this->_page-1)*$this->_onpage;	
		$this->view->current_page = $this->_page;
		$this->view->onpage = $this->_onpage;
	
		
	}
	
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$this->layout->action_title = "Список модулей";	
		
                $modules = Modules::getInstance()->ModulesSync();
		//$modules = Modules::getInstance()->fetchAll(null, 'priority DESC')->toArray();		
		$this->view->items = $modules;
	}

	
	/**
       * Инсталер модулей Ajax
       * В каждом модуле должен быть файл <Имя модуля>Install.php наследуемый от Ext_Common_InstallModuleAbstract
       * и  конфиг <имя модуля>.ini в котором прописываются базовые настройки модуля
       */
	public function installAction() {
          
         $installed = $this->_getParam('installed');
         $module_name = $this->_getParam('module_name');
        
        require_once APPLICATION_PATH.'/modules/'.$module_name.'/controllers/Admin/'.ucfirst($module_name).'Install.php';
        $r = new ReflectionClass(ucfirst($module_name).'Install');
        $install = $r->newInstanceArgs((array)$module_name);
                
        if (($this->_request->isXmlHttpRequest()) && ($this->_getParam('installed')))
        {
            $install->Uninstall();
            $module = Modules::getInstance()->getModuleByName($module_name);
           
          echo  '	<td class="main" style="color:red;">'.
			$module['title'].' 
		      </td>
                  <td class="main" >
			<strong>'.$module['name'].'</strong>
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
                                                  'target_url'=>'modules/install',
                                                  'url_data'	=>"{installed: ".$module['installed'].",module_name: '".$module['name']."'}",
                                                  'loader_img'=>"/img/horizontal_loader.gif"
                                                  ));
            
        }
        else
        {
            $install->Install();
            $module = Modules::getInstance()->getModuleByName($module_name);
           
          echo  ' <td class="main" " style="color:green;">'.
			$module['title'].' 
		      </td>
                  <td class="main" >
			<strong>'.$module['name'].'</strong>
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
                                                  'target_url'=>'modules/install',
                                                  'url_data'	=>"{installed: ".$module['installed'].",module_name: '".$module['name']."'}",
                                                  'loader_img'=>"/img/horizontal_loader.gif"
                                                  ));;
           
	} 
	
	exit;  
    }
	
   public function visibilityAction()
   {
       if ($this->_request->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $row = Modules::getInstance()->find($id)->current();
            if ($row != null) {
                $row->visible = abs($row->visible - 1);
                $row->save();
                echo '<img src="/img/admin/visible_' . $row->visible . '.png" />';
            } else {
                echo 'error';
            }
        }
        exit;

   }


   public function editAction(){
		$name = $this->_getParam('name', '');
		if ($name){
			$module = Modules::getInstance()->fetchRow("name='$name'");
			if ($module!=null){
				$item = $module;
				if ($this->_request->isPost()){						
					$data = $this->trimFilter($this->_getParam('edit'));			
					if ($data['title']!=''){
						$item->setFromArray($data);
						$id =  $item->save();				
						$this->view->ok=1;
					} else{
						$this->view->err=1;				
					}
				
				}
			}
		}
		
		$this->view->item = $item;
		$this->layout->action_title = "Редактировать модуль";
	}

	
	public function deleteAction(){
		$name = $this->_getParam('name', '');
		if ($name ){
			$module = Modules::getInstance()->fetchRow("name='$name'");
			if ($module!=null){
				$module->delete();	
				@unlink(DIR_MODULES.$name.DS.'install.txt');	
			}		
			
			$this->_redirect($this->_curModule);
		}
	}
}
