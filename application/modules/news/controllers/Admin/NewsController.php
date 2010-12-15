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

    public function init() {
        $this->view->addHelperPath(Zend_Registry::get('helpersPaths') , 'View_Helper') ;
        $this->view->
        //$this->view->addHelperPath(DIR_LIBRARY.'ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
        $this->_id_page = $this->_getParam('id_page');
        $this->view->id_page = $this->_id_page;
        if ($this->_id_page!=null) {
            $this->_owner = Pages::getInstance()->find($this->_id_page)->current();
            if ($this->_owner!=null) {
                $this->view->layout()->title = $this->_owner->name;
            }
        }
        $this->view->lang = $lang = $this->_getParam('lang','ru');
        $this->view->currentModul = $this->_curModule = SP.'news'.SP.$lang.SP.$this->getRequest()->getControllerName();
    }

    public function indexAction() {
        $this->view->layout()->action_title = "Список элементов";
        $page = $this->view->current_page = $this->_getParam('page', 1);
        $onPage = $this->view->onpage = 50;
        $this->view->counter=($page-1)*$onPage;
        $this->view->news = News::getInstance()->fetchAll();
       // $this->view->total = News::getInstance()->getCount("id_page='".$this->_id_page."'");

    }



    public function addAction() {
        $this->view->layout()->action_title = "Создать элемент";
        $lang = $this->getRequest()->getParam('lang','ru');
        $this->view->currentModul = $curModul = SP.'news'.SP.$lang.SP.$this->getRequest()->getControllerName();
        $this->view->lang = $lang;
        //$this->view->peoples = Peoples::getInstance()->fetchAll(null, 'priority DESC');
        $page_id = $this->_getParam('pageid');
        $this->view->modul_page = $page = Pages::getInstance()->getPage($page_id);
        $this->view->page = $page;
        $id = $this->_getParam('id');
        
        $form = new Form_FormNews();
     	
        $this->processForm($form, $this->getRequest());
			
       
        $this->view->form = $form;        
    }

    public function editAction() {
        $this->view->layout()->action_title = "Редактировать элемент";
        $id = (int)$this->getRequest()->getParam('id');
        $lang = $this->getRequest()->getParam('lang','ru');
        $this->view->currentModul = $curModul = SP.'news'.SP.$lang.SP.$this->getRequest()->getControllerName();
        $this->view->lang = $lang;
        //$novost = News::getInstance()->getNewById($id);
        // $this->view->peoples = Peoples::getInstance()->fetchAll(null, 'priority DESC');
        //print_r($novost->toArray());
        $page_id = $this->_getParam('pageid');
        $this->view->modul_page =$page = Pages::getInstance()->getPage($page_id);

        
        $row = News::getInstance()->find($id)->current();
        //if (isset($row->small_img))
        $data['small_img'] = '/pics/news/small_img_thumb/'.$row->small_img;
        echo  '/pics/news/small_img_thumb/'.$row->small_img;
        $form = new Form_FormNews($data);
        
        	if ($row!=null){
                        
        		$form->populate($row->toArray()); 
                       print_r($form);                       
        	}
                
        $this->processForm($form, $this->getRequest());
      
        $this->view->form = $form;
        
           // $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
       
    }


    public function checkurlAction() {
        $url = $this->_getParam('url', '');
        $id = $this->_getParam('id', '');
        if ($url) {
            $item = News::getInstance()->fetchRow("url='$url' AND id!='$id'");
            if ($item!=null) {
                echo 'err';
            } else echo 'ok';

        }
        exit;
    }

    public function deleteAction() {
        $lang = $this->getRequest()->getParam('lang','ru');
        $curModul = SP.'news'.SP.$lang.SP.$this->getRequest()->getControllerName();
        if(!$this->_hasParam('id')) {
            $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
        }
        else {
            $id = (int)$this->getRequest()->getParam('id');

            $novost = News::getInstance()->getNewById($id);

            News::getInstance()->deleteNew($id);
            $sortSession = new Zend_Session_Namespace('sortSearch');
            $pageSession = new Zend_Session_Namespace('pageSearch');

            if (isset($pageSession->page)) {
                $page_link="/page/".$pageSession->page;
            } else $page_link="";

            if (isset($sortSession->sort)) {
                $sort_link="/sort/".strtolower($sortSession->sort);
            } else $sort_link="";

            $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
        }

    }

    public function setmainAction() {
        $lang = $this->getRequest()->getParam('lang','ru');
        $curModul = SP.'news'.SP.$lang.SP.$this->getRequest()->getControllerName();
        if(!$this->_hasParam('id')) {
            $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
        }
        else {
            $id = (int)$this->getRequest()->getParam('id');
            News::getInstance()->setMainNew($id);
            $sortSession = new Zend_Session_Namespace('sortSearch');
            $pageSession = new Zend_Session_Namespace('pageSearch');

            if (isset($pageSession->page)) {
                $page_link="/page/".$pageSession->page;
            } else $page_link="";

            if (isset($sortSession->sort)) {
                $sort_link="/sort/".strtolower($sortSession->sort);
            } else $sort_link="";

            $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
        }
    }


    public function unsetmainAction() {
        $lang = $this->getRequest()->getParam('lang','ru');
        $curModul = SP.'news'.SP.$lang.SP.$this->getRequest()->getControllerName();
        if(!$this->_hasParam('id')) {
            $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
        }
        else {
            $id = (int)$this->getRequest()->getParam('id');
            News::getInstance()->unsetMainNew($id);
            $sortSession = new Zend_Session_Namespace('sortSearch');
            $pageSession = new Zend_Session_Namespace('pageSearch');

            if (isset($pageSession->page)) {
                $page_link="/page/".$pageSession->page;
            } else $page_link="";

            if (isset($sortSession->sort)) {
                $sort_link="/sort/".strtolower($sortSession->sort);
            } else $sort_link="";

            $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
        }
    }

    public function activateAction() {
        if($this->_hasParam('id')) {
            $id = (int)$this->getRequest()->getParam('id');
            Pages::getInstance()->pubPage($id);
        }
        $this->view->lang = $this->_getParam('lang');
        $lang = $this->_hasParam('lang') ? $this->getParam('lang') : 'ru';
        $this->_redirect("/news/$lang/admin_news/");
    }

    public function unactivateAction() {
        if($this->_hasParam('id')) {
            $id = (int)$this->getRequest()->getParam('id');
            Pages::getInstance()->unpubPage($id);
        }
        $lang = $this->_getParam('lang');
        $this->view->lang = $lang;
        $this->_redirect("/news/$lang/admin_news/");
    }

    /**
     * 
     * @param Ext_Form $form
     * @param Zend_Controller_Request_Http $request
     */
    private function processForm($form, $request)
    {
    	if ($request->isPost())
        {
    	        	
            if ($form->isValid($this->_getAllParams()) && ($request->getActionName() === 'add'))
            {
    			
                        $this->reciveFile(ROOT_DIR.'pics/news/small_img/');
                        $fileInfo = $uploads->getFileInfo();
                        $thumb = Ext_Common_PhpThumbFactory::create(ROOT_DIR.'pics/news/small_img/'.$fileInfo['name']);
                        echo "Image create";
                        $thumb->resize(100);
                        echo "Resize ok";
                        $thumb->save(ROOT_DIR.'pics/news/small_img_thumb/'.$fileInfo['name']);
                        echo "sAVE";
                        $id = News::getInstance()->addNews($form->getValues());
    			$this->_redirect($this->_curModule.'/edit/id/'.$id);
                        
                        
                        
    			
            } 
            else 
            {
                
                  $uploads = $this->reciveFile(ROOT_DIR.'pics/news/small_img/');
                  $fileInfo = $uploads->getFileInfo();
                 // $fileInfo['small_img']['name'];
                  $form->getElement('small_img')->setValue($fileInfo['small_img']['name']);
                  //echo ROOT_DIR.'pics/news/small_img/'.$fileInfo['name'];
                  echo "Begin";
                  //echo ROOT_DIR.'pics/news/small_img/'.$fileInfo['name'];
                  $thumb = Ext_Common_PhpThumbFactory::create(ROOT_DIR.'pics/news/small_img/'.$fileInfo['small_img']['name']);
                  echo "Image create";
                  $thumb->resize(100);
                  echo "Resize ok";
                  $thumb->save(ROOT_DIR.'pics/news/small_img_thumb/'.$fileInfo['small_img']['name']);
                  echo "sAVE";
    		  News::getInstance()->editNews($form->getValues(), (int)$form->getValue('id'));
                  
    	    }
        }
    }
    
    private function reciveFile($destination)
    {
       $upload = new Zend_File_Transfer_Adapter_Http();
       $upload->setDestination($destination);
       try {
       // upload received file(s)
       $upload->receive();
        } catch (Zend_File_Transfer_Exception $e) {
              $e->getMessage();
        }
        return $upload;
        
    }
    
    public function installAction()
    {

       require_once 'NewsInstall.php';
       echo 'Begin';
       $install = new News_Admin_NewsInstall('news');
     // $install->Uninstall();
       $install->Install();
       
    }
    
   


    
}