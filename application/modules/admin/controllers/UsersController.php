<?php

class Admin_UsersController extends MainAdminController {

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
        $this->view->addHelperPath(Zend_Registry::get('helpersPaths'), 'View_Helper');
        $this->view->addScriptPath(DIR_LIBRARY . 'Ext/View/Scripts/');

        $ini = new Ext_Common_Config('admin', 'users');
        $this->_basePicsPath = $ini->basePicsPath;
        $this->_onPage = $ini->countOnPage;

        $this->view->layout()->title = "Управление профилями";
        $this->view->lang = $lang = $this->_getParam('lang', 'ru');
        $this->view->currentModul = $this->_curModule = SP . 'admin' . SP . $lang . SP . $this->getRequest()->getControllerName();
    }

    public function indexAction() {
        $this->view->layout()->action_title = "Список пользователей";
        $page = $this->view->current_page = $this->_getParam('page', 1);

        $paginator = Users::getInstance()->getAll($this->_onPage, $page);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');

        $this->view->users = $paginator->getCurrentItems();
        $this->view->paginator = $paginator;
    }

    /**
     * изменение активности
     */
    public function changeactiveAction() {
        // проверка пришел ли запрос аяксом
        if ($this->_request->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $row = Users::getInstance()->find($id)->current();
            if ($row != null) {
                $row->activity = abs($row->activity - 1);
                $row->save();
                echo '<img src="/img/admin/active_' . $row->activity . '.png" />';
            } else {
                echo 'error';
            }
        }
        exit;
    }

    /**
     * удаление элемента
     */
    public function deleteAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $row = Users::getInstance()->find($id)->current();
            if ($row != null) {
                $row->delete();
                echo 'ok';
            } else {
                echo 'error';
            }
        }
        exit;
    }

    public function addAction() {
        $this->view->layout()->action_title = "Создать элемент";
        $row = Users::getInstance()->fetchNew();

        $form = new Form_FormUsers();

        if (!is_null($row) && $this->_request->isPost()) {
            $save_row = $this->processForm($form, $row);
            if ($save_row->id)
                $this->_redirect($this->_curModule . '/edit/id/' . $save_row->id);
        }

        $form->getElement('activity')->setChecked(true);

        $this->view->form = $form;
    }

    public function editAction() {

        $id = $this->_getParam('id');

        if ($id) {
            Form_FormUsers::setRecordId($id);
            $this->view->layout()->action_title = "Редактировать элемент";
            $row = Users::getInstance()->find((int) $this->_getParam('id'))->current();
        } else {
            $this->_redirect('/404');
        }

        $form = new Form_FormUsers();

        if (!is_null($row)) {
            if ($this->_request->isPost()) {
                $save_row = $this->processForm($form, $row);
                $data = $form->getValues();
                $form->populate($data);
            } else {
                $form->populate($row->toArray());
            }
        }

        $this->view->form = $form;

        // $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
    }

    /**
     *
     * @param Ext_Form $form
     * @param Zend_Controller_Request_Http $request
     */
    private function processForm($form, $row) {

        if ($this->_request->isPost()) {
            // добавление записи в базу
            if ($form->isValid($this->_getAllParams()) && $row->id == '') {
                $row = Users::getInstance()->addUser($row, $form->getValidValues($form->getValues()));
                // редактирование записи
            } elseif ($form->isValid($this->_getAllParams()) && $row->id > 0) {
                $row = Users::getInstance()->editUser($row, $form->getValidValues($this->_getAllParams()));
            }

            if (!is_null($row)){
                $this->view->ok = 1;
            }

            return $row;
        }
    }

}

