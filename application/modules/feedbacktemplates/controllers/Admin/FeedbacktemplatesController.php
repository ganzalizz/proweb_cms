<?php

/**
 * Admin_FeedbackTemplatesController
 *
 * @author Vitali
 * @version
 */

class Feedbacktemplates_Admin_FeedbacktemplatesController extends MainAdminController {

    /**
     * @var string
     */
    private $_curModule = null;
    /**
     * items per page
     *
     * @var int
     */
    private $_onpage = 50;

    private $_page = null;
    /**
     * offset
     *
     * @var int
     */
    private $_offset = null;

    private $_owner = null;

    private $_id_page = null;

    public $_vertical_img_small_sizes = array('width'=>43, 'height'=>57);
    public $_vertical_img_medium_sizes = array('width'=>230, 'height'=>315);
    public $_img_small_sizes = array('width'=>94, 'height'=>57);
    public $_img_medium_sizes = array('width'=>230, 'height'=>156);

    public function init() {
        $this->initView();
        $this->view->caption = 'Шаблоны обратной связи';
        $id_page = $this->_getParam('id_page',0);
        if ($id_page) {
            $this->_id_page = $id_page;
            $this->view->owner =$this->_owner =  Pages::getInstance()->find($id_page)->current();
            $this->view->id_page = $id_page;
        }
        $lang = $this->_getParam('lang','ru');
        $this->view->currentModule = $this->_curModule = '/'.'feedbacktemplates'.'/'.$lang.'/'.$this->getRequest()->getControllerName();
        $this->_page = $this->_getParam('page', 1);
        $this->_offset =($this->_page-1)*$this->_onpage;
        $this->view->current_page = $this->_page;
        $this->view->onpage = $this->_onpage;
    }

    /**
     * The default action - show the home page
     */
    public function indexAction() {
        $this->view->child_name = 'Список документов';
        $id_page = $this->_getParam('id_page',0);
        if ($id_page) {
            $where = "id_page=".(int)$id_page;
            $this->view->owner =$owner =  Pages::getInstance()->find($id_page)->current();
            $this->view->id_page = $id_page;
            $this->view->child_name = $owner->name;
        } else {
            $where = null;
        }
        $this->view->all = FeedbackTemplates::getInstance()->fetchAll($where,'');
//$this->view->child_name = 'Список документов';
    }

    public function addAction() {
        if ($this->getParam('id')) {
            $item = FeedbackTemplates::getInstance()->find($this->getParam('id'))->current();
        } else {
            $item = FeedbackTemplates::getInstance()->createRow();
        }

        $this->view->content = $this->getFck('edit[content]', '100%','300');
        $this->view->fields = $this->getFck('edit[fields]', '100%','300');

        if ($this->_request->isPost()) {
            $data = $this->trimFilter($this->_getParam('edit'));
            $data['id_page'] = 0;
            if ($data['name']!='' && $data['content'] != '') {
                $item->setFromArray($data);
                $id =  $item->save();
                $this->view->ok=1;
                $this->_redirect($this->_curModule.'/edit/id/'.$item->id);
            }
            else {
                $this->view->err=1;
            }
        }

        $this->view->item = $item;
        if (isset($this->_owner->name)) {
            $this->view->child_name = $this->_owner->name;
        }
    }

    public function editAction() {
        if ($this->getParam('id')) {
            $item = FeedbackTemplates::getInstance()->find($this->getParam('id'))->current();
        } else {
            $item = FeedbackTemplates::getInstance()->createRow();
        }

        $this->view->content = $this->getFck('edit[content]', '100%','300');

        if ($this->_request->isPost()) {
            $data = $this->trimFilter($this->_getParam('edit'));
            if ($data['name']!='' ) {
                $item->setFromArray($data);
                $id =  $item->save();
            }
            $this->view->ok=1;
        } else {
            $this->view->err=0;
        }


        $this->view->item = $item;
        if (isset($this->_owner->name)) {
            $this->view->child_name = $this->_owner->name;
        }
//$this->view->places = Places::getInstance()->fetchAll(null, 'priority DESC');
    }

    public function deleteAction() {
        $id = $this->_getParam('id',0);
        $item = FeedbackTemplates::getInstance()->find($id)->current();
        if (!is_null($item)) {
            $item->delete();
        }
        $this->_redirect($this->_curModule.'/index/id_page/'.$this->_id_page);

    }

    public function activateAction() {
        $id = $this->_getParam('id',0);
        $item = FeedbackTemplates::getInstance()->find($id)->current();
        if (!is_null($item)) {
            $item->active = abs($item->active-1);
            $item->save();
        }
        $this->_redirect($this->_curModule);
    }
}
