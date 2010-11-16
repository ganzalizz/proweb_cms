<?php

class Portfolio_Admin_PortfolioController extends MainAdminController {
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

    public function init() {
        $this->view->addHelperPath(Zend_Registry::get('helpersPaths') , 'View_Helper') ;
        $this->_id_page = $this->_getParam('id_page');
        $this->view->id_page = $this->_id_page;
        if ($this->_id_page!=null) {
            $this->_owner = Pages::getInstance()->find($this->_id_page)->current();
            if ($this->_owner!=null) {
                $this->view->layout()->title = $this->_owner->name;
            }
        }
        $this->view->lang = $lang = $this->_getParam('lang','ru');
        $this->view->currentModul = SP.'portfolio'.SP.$lang.SP.$this->getRequest()->getControllerName();
    }

    public function indexAction() {
        $this->view->layout()->action_title = "Список элементов";
        $page = $this->view->current_page = $this->_getParam('page', 1);
        $onPage = $this->view->onpage = 50;
        $this->view->counter=($page-1)*$onPage;
        $this->view->portfolio = Portfolio::getInstance()->fetchAll();
        $this->view->total = Portfolio::getInstance()->getCount("id_page='".$this->_id_page."'");

    }



    public function addAction() {
        $this->view->layout()->action_title = "Создать элемент";
        $lang = $this->getRequest()->getParam('lang','ru');
        $this->view->currentModul = $curModul = SP.'portfolio'.SP.$lang.SP.$this->getRequest()->getControllerName();
        $this->view->lang = $lang;
        //  $this->view->peoples = Peoples::getInstance()->fetchAll(null, 'priority DESC');
        $page_id = $this->_getParam('pageid');
        $this->view->modul_page = $page = Pages::getInstance()->getPage($page_id);
        $this->view->page = $page;
        if ($this->_request->isPost()) {

            $data = $this->getRequest()->getParams();
            $new = $data['new'];
            if ($new['created_at']!='') {
                $adate = preg_match('/([\d]{2}).+([\d]{2}).+([\d]{4})/is', $new['created_at'], $matches);
                $new['created_at'] = $matches[3].'-'.$matches[2].'-'.$matches[1];
            } else {
                $new['created_at'] = date('Y-m-d H:i:s');

            }
            $new['name']=trim($new['name']);
            if (isset($new['pub']) && $new['pub']=='on') {
                $new['pub'] = 1;
            }
            else {
                $new['pub'] = 0;
            }
            (isset($new['main']) && $new['main']=='on' ?$new['main'] = 1 :$new['main'] = 0);
            if (isset($page)) {
                $new['page_id']=$page->id;
                $new['type']=$page->template;
            }

            $post_data['title'] = $new['name'];
            $post_data['text'] = $new['content'];

            $id = Portfolio::getInstance()->addPortfolio($new);
            $item = Portfolio::getInstance()->find($id)->current();
            // иконка
            $img_name = $_FILES['image_small']['name'];
            $img_source = $_FILES['image_small']['tmp_name'];

            if ($img_name!='' && $img_source!='' ) {
                $ext = @end(explode('.', $img_name));
                $small_img = DIR_PUBLIC.'pics/portfolio/'.$id.'_small.'.$ext;
                if(copy($img_source, $small_img)) {
                    $item->small_img = $id.'_small.'.$ext;
                    $item->save();
                }
            }
            // картинка
            $img_name = $_FILES['image_big']['name'];
            $img_source = $_FILES['image_big']['tmp_name'];
            $delete_img = $this->_getParam('delete_img_big');
            if ($img_name!='' && $img_source!='' && !$delete_img) {
                $ext = @end(explode('.', $img_name));

                $big_img = DIR_PUBLIC.'pics/portfolio/'.$id.'_big.'.$ext;
                if(copy($img_source, $big_img)) {
                    $item->big_img = $id.'_big.'.$ext;
                    $item->save();
                }
            }
        }
        $fck1 = $this->getFck('new[intro]', '100%', '200');
        $this->view->fck_intro = $fck1;
        $fck2 = $this->getFck('new[content]', '100%', '300');
        $this->view->fck_content = $fck2;
    }

    public function editAction() {
        $this->view->layout()->action_title = "Редактировать элемент";
        $id = (int)$this->getRequest()->getParam('id');
        $lang = $this->getRequest()->getParam('lang','ru');
        $this->view->currentModul = $curModul = SP.'portfolio'.SP.$lang.SP.$this->getRequest()->getControllerName();
        $this->view->lang = $lang;
        $novost = Portfolio::getInstance()->getPortfolioById($id);
        // $this->view->peoples = Peoples::getInstance()->fetchAll(null, 'priority DESC');
        //print_r($novost->toArray());
        $page_id = $this->_getParam('pageid');
        $this->view->modul_page =$page = Pages::getInstance()->getPage($page_id);

        if ($this->_request->isPost()) {
            $id = (int)$this->getRequest()->getParam('id');
            $data = $this->getRequest()->getParams();
            
            if ($data['new']['name']!='' ) {
                $new = $data['new'];
                if ($new['created_at']!='') {
                    $adate = preg_match('/([\d]{2}).+([\d]{2}).+([\d]{4})/is', $new['created_at'], $matches);
                    $new['created_at'] = $matches[3].'-'.$matches[2].'-'.$matches[1];
                } else {
                    $new['created_at'] = date('Y-m-d H:i:s');
                }
                $new['name']=trim($new['name']);
                if (isset($new['pub']) && $new['pub']=='1') {
                    $new['pub'] = 1;
                }
                else {
                    $new['pub'] = 0;
                }
                (isset($new['main']) && $new['main']=='1' ?	$new['main'] = 1 :$new['main'] = 0);
                Portfolio::getInstance()->editPortfolio($new,$id);
                // иконка
                $img_name = $_FILES['image_small']['name'];
                $img_source = $_FILES['image_small']['tmp_name'];
                $delete_img = $this->_getParam('delete_img_small');
                if ($img_name!='' && $img_source!='' && !$delete_img) {
                    $item = Portfolio::getInstance()->getPortfolioById($id);
                    $ext = @end(explode('.', $img_name));
                    $small_img = DIR_PUBLIC.'pics/portfolio/'.$id.'_small.'.$ext;
                    $big_img = DIR_PUBLIC.'pics/portfolio/'.$id.'_big.'.$ext;
                    if(copy($img_source, $small_img)) {
                        $item->small_img = $id.'_small.'.$ext;
                        $item->save();
                    }
                } else if ($delete_img) {
                    $item = Portfolio::getInstance()->getPortfolioById($id);
                    @unlink(DIR_PUBLIC.'pics/portfolio/'.$item->small_img);
                    $item->small_img='';
                    $item->save();
                }
                // картинка
                $img_name = $_FILES['image_big']['name'];
                $img_source = $_FILES['image_big']['tmp_name'];
                $delete_img = $this->_getParam('delete_img_big');
                if ($img_name!='' && $img_source!='' && !$delete_img) {
                    $item = Portfolio::getInstance()->getPortfolioById($id);
                    $ext = @end(explode('.', $img_name));

                    $big_img = DIR_PUBLIC.'pics/portfolio/'.$id.'_big.'.$ext;
                    if(copy($img_source, $big_img)) {
                        $item->big_img = $id.'_big.'.$ext;
                        $item->save();
                    }
                } else if ($delete_img) {
                    $item = Portfolio::getInstance()->getPortfolioById($id);
                    @unlink(DIR_PUBLIC.'pics/portfolio/'.$item->big_img);
                    $item->big_img='';
                    $item->save();
                }

                $this->editMeta('portfolio', $id);
                $sortSession = new Zend_Session_Namespace('sortSearch');
                $pageSession = new Zend_Session_Namespace('pageSearch');

                if (isset($pageSession->page)) {
                    $page_link="/page/".$pageSession->page;
                } else $page_link="";

                if (isset($sortSession->sort)) {
                    $sort_link="/sort/".strtolower($sortSession->sort);
                } else $sort_link="";

                $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
            } else $this->view->err=1;
        }

        $fck1 = $this->getFck('new[intro]', '100%', '200');
        $this->view->fck_intro = $fck1;
        $fck2 = $this->getFck('new[content]', '100%', '300');
        $this->view->fck_content = $fck2;
        $this->view->options = $this->getMeta('portfolio', $id);
        $novost->created_at = $this->dateFromDb($novost->created_at);
        $this->view->new = $novost;
    }

    public function pubAction() {
        $lang = $this->getRequest()->getParam('lang','ru');
        $curModul = SP.'portfolio'.SP.$lang.SP.$this->getRequest()->getControllerName();
        if(!$this->_hasParam('id')) {
            $this->_redirect($curModul);
        }
        else {
            $id = (int)$this->getRequest()->getParam('id');
            Portfolio::getInstance()->pubPortfolio($id);

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

    public function unpubAction() {

        $lang = $this->getRequest()->getParam('lang','ru');
        $curModul = SP.'portfolio'.SP.$lang.SP.$this->getRequest()->getControllerName();
        if(!$this->_hasParam('id')) {
            $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
        }
        else {
            $id = (int)$this->getRequest()->getParam('id');
            Portfolio::getInstance()->unpubPortfolio($id);
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


    public function checkurlAction() {
        $url = $this->_getParam('url', '');
        $id = $this->_getParam('id', '');
        if ($url) {
            $item = Portfolio::getInstance()->fetchRow("url='$url' AND id!='$id'");
            if ($item!=null) {
                echo 'err';
            } else echo 'ok';

        }
        exit;
    }

    public function deleteAction() {
        $lang = $this->getRequest()->getParam('lang','ru');
        $curModul = SP.'portfolio'.SP.$lang.SP.$this->getRequest()->getControllerName();
        if(!$this->_hasParam('id')) {
            $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
        }
        else {
            $id = (int)$this->getRequest()->getParam('id');

            $novost = Portfolio::getInstance()->getPortfolioById($id);

            Portfolio::getInstance()->deletePortfolio($id);
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
        $curModul = SP.'portfolio'.SP.$lang.SP.$this->getRequest()->getControllerName();
        if(!$this->_hasParam('id')) {
            $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
        }
        else {
            $id = (int)$this->getRequest()->getParam('id');
            Portfolio::getInstance()->setMainPortfolio($id);
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
        $curModul = SP.'portfolio'.SP.$lang.SP.$this->getRequest()->getControllerName();
        if(!$this->_hasParam('id')) {
            $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
        }
        else {
            $id = (int)$this->getRequest()->getParam('id');
            Portfolio::getInstance()->unsetMainPortfolio($id);
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
        $this->_redirect("/portfolio/$lang/admin_portfolio/");
    }

    public function unactivateAction() {
        if($this->_hasParam('id')) {
            $id = (int)$this->getRequest()->getParam('id');
            Pages::getInstance()->unpubPage($id);
        }
        $lang = $this->_getParam('lang');
        $this->view->lang = $lang;
        $this->_redirect("/portfolio/$lang/admin_portfolio/");
    }



    private function editRoute($data) {
        Loader::loadCommon('Router');

        if(!Router::getInstance()->replaceRoute($data, 'index', 'index', 'portfolio')) {
            return "Такой URL уже существует!";
        }

        return '';
    }
}