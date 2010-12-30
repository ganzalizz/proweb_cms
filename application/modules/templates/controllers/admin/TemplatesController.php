<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Templates_Admin_TemplatesController extends MainAdminController {

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
        $ini = new Ext_Common_Config('templates', 'backend');
        $this->_basePicsPath = $ini->basePicsPath;
        $this->_onPage = $ini->countOnPage;

        $this->checkDirs();

        $this->view->layout()->title = "Шаблоны сайтов";
        $this->view->lang = $lang = $this->_getParam('lang', 'ru');
        $this->view->currentModul = $this->_curModule = SP . 'templates' . SP . $lang . SP . $this->getRequest()->getControllerName();
    }

    public function indexAction() {
        $this->view->layout()->action_title = "Список элементов";
        $page = $this->view->current_page = $this->_getParam('page', 1);

        $paginator = Templates::getInstance()->getAll($this->_onPage, $page);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
        
        $this->view->templates = $paginator->getCurrentItems();
        $this->view->paginator = $paginator;
    }

    public function addAction() {
        $this->view->layout()->action_title = "Создать элемент";
        $row = Templates::getInstance()->fetchNew();

        $form = new Form_FormTemplates();

        if (!is_null($row) && $this->_request->isPost()) {
            $save_row = $this->processForm($form, $row);
            if ($save_row->id)
                $this->_redirect($this->_curModule . '/edit/id/' . $save_row->id);
        }
        
        $form->getElement('is_active')->setChecked(true);

        $this->view->form = $form;
    }

    public function editAction() {

        $id = $this->_getParam('id');
        if ($id) {
            Form_FormTemplates::setRecordId($id);
            $this->view->layout()->action_title = "Редактировать элемент";
            $row = Templates::getInstance()->find((int) $this->_getParam('id'))->current();
        } else {
            $this->_redirect('/404');
        }

        $form = new Form_FormTemplates();

        if (!is_null($row)) {

            if ($this->_request->isPost()){
                $save_row = $this->processForm( $form, $row );
                $data = $form->getValues();
                $form->populate($data);
            } else {
                $form->populate($row->toArray());
            }
            if ($row->template_image) {
                $form->getElement('template_image')->setAttrib('template_image', '/pics/templates/thumbs/' . $row->template_image);
            }
        }




        $this->view->form = $form;

        // $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
    }

    /**
     * изменение активности
     */
    public function changeactiveAction() {
        // проверка пришел ли запрос аяксом
        if ($this->_request->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $row = Templates::getInstance()->find($id)->current();
            if ($row != null) {
                $row->is_active = abs($row->is_active - 1);
                $row->save();
                echo '<img src="/img/admin/active_' . $row->is_active . '.png" />';
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
            $row = Templates::getInstance()->find($id)->current();
            if ($row != null) {
                if ($row->template_image != '') {
                    @unlink($this->_basePicsPath . 'template_image/' . $row->template_image);
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
     *
     * @param Ext_Form $form
     * @param Zend_Controller_Request_Http $request
     */
    private function processForm($form, $row) {

        if ($this->_request->isPost()) {
            $flag_save = false;
            // добавление записи в базу
            if ($form->isValid($this->_getAllParams()) && $row->id == '') {
                $row = Templates::getInstance()->addTemplate($row, $form->getValidValues($form->getValues()));
                $flag_save = true;
                // редактирование записи
            } elseif ($form->isValid($this->_getAllParams()) && $row->id > 0) {
                $row = Templates::getInstance()->editTemplate($row, $form->getValidValues($this->_getAllParams()));
                $flag_save = true;
            }

            if (!is_null($row) && $flag_save){ // запись в базе создана загружаем картинки
                $aploaded_images = $this->reciveFiles($row->id);
                if (isset($aploaded_images['template_image']) && !$this->_getParam('template_image_delete')) {

                    $thumb = Ext_Common_PhpThumbFactory::create($aploaded_images['template_image']);
                    $thumb->setOptions(array('jpegQuality' => 100));
                    $thumb->resize(130,150);
                    $thumb->save($this->_basePicsPath . 'thumbs/' . basename($aploaded_images['template_image']));

                    if ($row->template_image != '' && $row->template_image != basename($aploaded_images['template_image'])) {
                        @unlink($this->_basePicsPath . 'template_image/' . $row->template_image);
                        @unlink($this->_basePicsPath . 'thumbs/' . $row->template_image);
                    }

                    $row->template_image = basename($aploaded_images['template_image']);
                } elseif ($this->_getParam('template_image_delete')) {
                    @unlink($this->_basePicsPath . 'template_image/' . $row->template_image);
                    @unlink($this->_basePicsPath . 'thumbs/' . $row->template_image);
                    $row->template_image = '';
                }

                $row->save();
                $this->view->ok = 1;
            }
            return $row;
        }
    }

    /**
     *
     * @param unknown_type $destination
     */
    private function reciveFiles($id) {
        $upload = new Zend_File_Transfer_Adapter_Http( );

        $uploaded_images = array();

        if (!$id) {
            return $uploaded_images;
        }

        // загрузка большой картинки
        if ($upload->getFileInfo('template_image')) {
            $big_img_name = $this->_basePicsPath . 'template_image/' . $id . '_' . basename($upload->getFileName('template_image'));
            $upload->addFilter(
                    'Rename',
                    array(
                        'target' => $big_img_name,
                        'overwrite' => true
                    ),
                    'template_image'
            );
            if ($upload->receive('template_image')) {
                $uploaded_images['template_image'] = $big_img_name;
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
        if (!is_dir($this->_basePicsPath . '/template_image')) {
            mkdir($this->_basePicsPath . '/template_image', 0777, true);
        }
        if (!is_dir($this->_basePicsPath . '/thumbs')) {
            mkdir($this->_basePicsPath . '/thumbs', 0777, true);
        }
    }
}
