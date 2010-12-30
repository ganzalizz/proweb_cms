<?php

/**
 * Admin_BlocksController
 * 
 * @author Grover
 * @version 1.0
 */
class Blocks_Admin_BlocksController extends MainAdminController {

    /**
     * @var string
     */
    private $_curModule = null;
    /**
     * items per page
     *
     * @var int
     */
    private $_onPage = null;
    /**
     * items per page
     *
     * @var int
     */
    private $_lang = null;
    private $_page = null;
    private $_owner = null;
    /**
     * offset
     *
     * @var int
     */
    private $_offset = null;

    public function init() {
        $this->view->addHelperPath(Zend_Registry::get('helpersPaths'), 'View_Helper');
        $this->view->addScriptPath(DIR_LIBRARY . 'Ext/View/Scripts/');
        $ini = new Ext_Common_Config('blocks', 'backend');
        $this->_onPage = $ini->countOnPage;

        $this->view->layout()->title = "Блоки";
        $this->view->lang = $lang = $this->_getParam('lang', 'ru');
        $this->view->currentModul = $this->_curModule = SP . 'blocks' . SP . $lang . SP . $this->getRequest()->getControllerName();
    }

    /**
     * The default action - show the home page
     */
    public function indexAction() {
        $this->view->layout()->action_title = "Список элементов";
        $page = $this->view->current_page = $this->_getParam('page', 1);

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
        $paginator = Blocks::getInstance()->getAll($this->_onPage, $page);
        $this->view->blocks = $paginator->getCurrentItems();
        $this->view->paginator = $paginator;
    }

    public function addAction() {
        $this->view->layout()->action_title = "Создать элемент";
        $row = Blocks::getInstance()->fetchNew();

        $form = new Form_FormBlocks();

        if (!is_null($row) && $this->_request->isPost()) {
            $save_row = $this->processForm($form, $row);
            if ($save_row->id)
                $this->_redirect($this->_curModule . '/edit/id/' . $save_row->id);
        }

        $form->removeElement('content');
        $form->getElement('priority')->setValue(500);
        $form->getElement('type')->setValue(array('text', 'text'));
        $form->getElement('active')->setChecked(true);

        $this->view->form = $form;
    }

    public function editAction() {

        $id = $this->_getParam('id');

        if ($id) {
            Form_FormBlocks::setRecordId($id);
            $this->view->layout()->action_title = "Редактировать элемент";
            $row = Blocks::getInstance()->find((int) $this->_getParam('id'))->current();
        } else {
            $this->_redirect('/404');
        }

        $form = new Form_FormBlocks();

        if (!is_null($row)) {
            if ($this->_request->isPost()) {
                $save_row = $this->processForm($form, $row);
                $data = $form->getValues();
                $form->populate($data);
            } else {
                $form->populate($row->toArray());
            }
        }

        if ($row->type == 'html') {
            $content = new Ext_Form_Element_CkEditor('content', array(
                        'label' => 'Содержание',
                        'decorators' => array(
                            array('ViewHelper'),
                            array('Errors')),
                        'filters' => array('StringTrim'),
                        'value' => $row->content
                    ));
            $form->getDisplayGroup('blockDataGroup')->addElement($content);
        }

        $this->view->form = $form;

        // $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
    }

    /**
     * изменение активности элемента
     *
     */
    public function activeAction() {
        if ($this->_hasParam('id')) {
            $id = (int) $this->getRequest()->getParam('id');
            $page = Blocks::getInstance()->find($id)->current();
            if (!is_null($page)) {
                $page->active = abs($page->active - 1);
                $page->save();
                Blocks_Cache::clean();
                /* if ($this->_request->isXmlHttpRequest()){
                  echo $page->active; exit;
                  } */
            }
        }
        $this->_redirect($this->_curModule);
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
                $row = Blocks::getInstance()->addBlocks($row, $form->getValidValues($form->getValues()));
                $this->view->ok = 1;
            // редактирование записи
            } elseif ($form->isValid($this->_getAllParams()) && $row->id > 0) {
                $row = Blocks::getInstance()->editBlocks($row, $form->getValidValues($this->_getAllParams()));
                $this->view->ok = 1;
            }

            return $row;
        }
    }

    /**
     * изменение активности
     */
    public function changeactiveAction() {
        // проверка пришел ли запрос аяксом
        if ($this->_request->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $row = Blocks::getInstance()->find($id)->current();
            if ($row != null) {
                $row->active = abs($row->active - 1);
                $row->save();
                echo '<img src="/img/admin/active_' . $row->active . '.png" />';
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
            $row = Blocks::getInstance()->find($id)->current();
            if ($row != null) {
                $row->delete();
                echo 'ok';
            } else {
                echo 'error';
            }
        }
        exit;
    }

}