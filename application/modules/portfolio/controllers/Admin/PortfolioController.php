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
    private $_curModule = null;
    private $_basePicsPath = null;
    private $_onPage = null;

    public function init() {
        $this->view->addHelperPath(Zend_Registry::get('helpersPaths'), 'View_Helper');
        $this->view->addScriptPath(DIR_LIBRARY . 'Ext/View/Scripts/');
        $ini = new Ext_Common_Config('portfolio', 'backend');
        $this->_basePicsPath = $ini->basePicsPath;
        $this->_onPage = $ini->countOnPage;

        $this->checkDirs();

        $this->view->layout()->title = "Портфолио";
        $this->view->lang = $lang = $this->_getParam('lang', 'ru');
        $this->view->currentModul = $this->_curModule = SP . 'portfolio' . SP . $lang . SP . $this->getRequest()->getControllerName();
    }

    public function indexAction() {
        $this->view->layout()->action_title = "Список элементов";
        $page = $this->view->current_page = $this->_getParam('page', 1);

        $paginator = Portfolio::getInstance()->getAll($this->_onPage, $page);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');

        $this->view->portfolio = $paginator->getCurrentItems();
        $this->view->paginator = $paginator;
    }

    public function addAction() {
        $this->view->layout()->action_title = "Создать элемент";

        $form = new Form_FormPortfolio( );

        if ($this->_request->isPost()) {

        }

        $this->processForm($form, $this->getRequest());

        $this->view->form = $form;
    }

    public function editAction() {

        $id = $this->_getParam('id');
        if ($id) {
            Form_FormPortfolio::setRecordId($id);
            $this->view->layout()->action_title = "Редактировать элемент";
            $row = Portfolio::getInstance()->find((int) $this->_getParam('id'))->current();
        } else {
            $this->view->layout()->action_title = "Создать элемент";
            $row = Portfolio::getInstance()->fetchNew();
        }

        $form = new Form_FormPortfolio();



        if (!is_null($row)) {

            if ($this->_request->isPost()){
                $data = $this->processForm( $form, $row );
                $form->populate($data);
            } else {
                $form->populate($row->toArray());
            }
            if ($row->image) {
                $form->getElement('image')->setAttrib('image', '/pics/portfolio/thumbs/' . $row->image);
            }
            if (!$id) {
                // по умолчанию создаем активный элемент
                $form->getElement('is_active')->setChecked(true);
            }
            if ($row->date_project != '') {
                $form->getElement('date_project')->setValue(date('d.m.Y', strtotime($row->date_project)));
            }
        }

        $this->view->form = $form;

        // $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
    }

    public function checkurlAction() {
        $url = $this->_getParam('url', '');
        $id = $this->_getParam('id', '');
        if ($url) {
            $item = Portfolio::getInstance()->fetchRow("url='$url' AND id!='$id'");
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
        if ($this->_request->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $row = Portfolio::getInstance()->find($id)->current();
            if ($row != null) {
                if ($row->image != '') {
                    @unlink($this->_basePicsPath . 'image/' . $row->image);
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
    public function changeactiveAction() {
        // проверка пришел ли запрос аяксом
        if ($this->_request->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $row = Portfolio::getInstance()->find($id)->current();
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
     * изменение отображение на главной
     */
    public function changemainAction() {
        // проверка пришел ли запрос аяксом
        if ($this->_request->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $row = Portfolio::getInstance()->find($id)->current();
            if ($row != null) {
                $row->is_main = abs($row->is_main - 1);
                $row->save();
                echo '<img src="/img/admin/main_' . $row->is_main . '.gif" />';
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
                $row = Portfolio::getInstance()->addPortfolio($row, $form->getValidValues($form->getValues()));
                $flag_save = true;
                // редактирование записи
            } elseif ($form->isValid($this->_getAllParams()) && $row->id > 0) {
                $row = Portfolio::getInstance()->editPortfolio($row, $form->getValidValues($this->_getAllParams()));
                $flag_save = true;
            }




            if (!is_null($row) && $flag_save){ // запись в базе создана загружаем картинки
                $aploaded_images = $this->reciveFiles($row->id);
                if (isset($aploaded_images['image']) && !$this->_getParam('image_delete')) {

                    $thumb = Ext_Common_PhpThumbFactory::create($aploaded_images['image']);
                    $thumb->setOptions(array('jpegQuality' => 100));
                    $thumb->resize(100);
                    $thumb->save($this->_basePicsPath . 'thumbs/' . basename($aploaded_images['image']));

                    if ($row->image != '' && $row->image != basename($aploaded_images['image'])) {
                        @unlink($this->_basePicsPath . 'image/' . $row->image);
                        @unlink($this->_basePicsPath . 'thumbs/' . $row->image);
                    }

                    $row->image = basename($aploaded_images['image']);
                } elseif ($this->_getParam('small_img_delete')) {
                    @unlink($this->_basePicsPath . 'image/' . $row->image);
                    @unlink($this->_basePicsPath . 'thumbs/' . $row->image);
                    $row->image = '';
                }

                $row->save();
                $this->view->ok = 1;
                
            }
            return $form->getValues();
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

        // загрузка изображения
        if ($upload->getFileInfo('image')) {
            $image_name = $this->_basePicsPath . 'image/' . $id . '_' .
                    basename($upload->getFileName('image'));
            $upload->addFilter(
                    'Rename',
                    array(
                        'target' => $image_name,
                        'overwrite' => true
                    ),
                    'image'
            );
            if ($upload->receive('image')) {
                $uploaded_images['image'] = $image_name;
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
        if (!is_dir($this->_basePicsPath . '/image')) {
            mkdir($this->_basePicsPath . '/image', 0777, true);
        }
        if (!is_dir($this->_basePicsPath . '/thumbs')) {
            mkdir($this->_basePicsPath . '/thumbs', 0777, true);
        }
    }
        
}