<?php

class Articles_Admin_ArticlesController extends MainAdminController {

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
        $this->view->addScriptPath(DIR_LIBRARY . 'Ext/View/Scripts/');
        $ini = new Ext_Common_Config('articles', 'backend');
        $this->_basePicsPath = $ini->basePicsPath;
        $this->_onPage = $ini->countOnPage;

        $this->checkDirs();
        $this->view->layout()->title = "Статьи";
        $this->view->lang = $lang = $this->_getParam('lang', 'ru');
        $module_name = $this->_request->getModuleName();
        $this->view->currentModul = $this->_curModule = SP . $module_name . SP . $lang . SP . $this->getRequest()->getControllerName();
    }

    public function indexAction() {
        $this->view->layout()->action_title = "Список элементов";
        $page = $this->view->current_page = $this->_getParam('page', 1);

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
        $paginator = Articles::getInstance()->getAll($this->_onPage, $page);
        $this->view->articles = $paginator->getCurrentItems();
        $this->view->paginator = $paginator;
    }

    public function addAction() {
        $this->view->layout()->action_title = "Создать элемент";
        $row = Articles::getInstance()->fetchNew();

        Articles_Form::setlightingOptions(Articles::$traffic_light);

        $form = new Articles_Form();

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
        Articles_Form::setlightingOptions(Articles::$traffic_light);
        if ($id) {
            $row = Articles::getInstance()->find((int) $this->_getParam('id'))->current();
            Articles_Form::setRecordId($id);
            Articles_Form::setlightingValue($row->lighting);
            $this->view->layout()->action_title = "Редактировать элемент";
        } else {
            $this->_redirect('/404');
        }

        $form = new Articles_Form();

        if (!is_null($row)) {

            if ($this->_request->isPost()) {
                $row = $this->processForm($form, $row);
            }
            $form->populate($row->toArray());
            if ($row->small_img) {
                $form->getElement('small_img')->setAttrib('small_img', '/pics/articles/thumbs/' . $row->small_img);
            }
            if ($row->big_img) {
                $form->getElement('big_img')->setAttrib('big_img', '/pics/articles/big_img/' . $row->big_img);
            }
        }

        $this->view->form = $form;

        // $this->_redirect($curModul.'/index/id_page/'.$this->_id_page);
    }

    /**
     * удаление элемента 
     */
    public function deleteAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $row = Articles::getInstance()->find($id)->current();
            if ($row != null) {
                if ($row->small_img != '') {
                    @unlink($this->_basePicsPath . 'small_img/' . $row->small_img);
                }
                if ($row->big_img != '') {
                    @unlink($this->_basePicsPath . 'big_img/' . $row->big_img);
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
            $row = Articles::getInstance()->find($id)->current();
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
            $row = Articles::getInstance()->find($id)->current();
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
     * установить снять флаг
     * горячая новость
     */
    public function changehotAction() {
        // проверка пришел ли запрос аяксом
        if ($this->_request->isXmlHttpRequest()) {
            $id = $this->_getParam('id');
            $row = Articles::getInstance()->find($id)->current();
            if ($row != null) {
                $row->is_hot = abs($row->is_hot - 1);
                $row->save();
                echo '<img src="/img/admin/hot_' . $row->is_hot . '.png" />';
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
            $flag_save = false;
            if ($form->isValid($this->_getAllParams()) && $row->id == '') {
                $row = Articles::getInstance()->addArticles($row, $form->getValidValues($form->getValues()));
                $flag_save = true;
                //TODO: разобраться с сохранением новой записи (во всех модулях!)
                // редактирование записи
            } elseif ($form->isValid($this->_getAllParams()) && $row->id > 0) {
                $row = Articles::getInstance()->editArticles($row, $form->getValidValues($this->_getAllParams()));
                $flag_save = true;
            }

            if (!is_null($row) && $flag_save) { // запись в базе создана загружаем картинки
                $aploaded_images = $this->reciveFiles($row->id);
                if (isset($aploaded_images['small_img']) && !$this->_getParam('small_img_delete')) {

                    $thumb = Ext_Common_PhpThumbFactory::create($aploaded_images['small_img']);
                    $thumb->setOptions(array('jpegQuality' => 95));
                    $thumb->resize(100);
                    $thumb->save($this->_basePicsPath . 'thumbs/' . basename($aploaded_images['small_img']));

                    if ($row->small_img != '' && $row->small_img != basename($aploaded_images['small_img'])) {
                        @unlink($this->_basePicsPath . 'small_img/' . $row->small_img);
                        @unlink($this->_basePicsPath . 'thumbs/' . $row->small_img);
                    }

                    $row->small_img = basename($aploaded_images['small_img']);
                } elseif ($this->_getParam('small_img_delete')) {
                    @unlink($this->_basePicsPath . 'small_img/' . $row->small_img);
                    @unlink($this->_basePicsPath . 'thumbs/' . $row->small_img);
                    $row->small_img = '';
                }
                if (isset($aploaded_images['big_img']) && !$this->_getParam('big_img_delete')) {

                    if ($row->big_img != '' && $row->big_img != basename($aploaded_images['big_img'])) {
                        @unlink($this->_basePicsPath . 'big_img/' . $row->big_img);
                    }
                    $row->big_img = basename($aploaded_images['big_img']);
                } elseif ($this->_getParam('big_img_delete')) {
                    @unlink($this->_basePicsPath . 'big_img/' . $row->big_img);
                    $row->big_img = '';
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

        // загрузка маленькой картинки
        if ($upload->getFileInfo('small_img')) {
            $small_img_name = $this->_basePicsPath . 'small_img/' . $id . '_' .
                    basename($upload->getFileName('small_img'));
            $upload->addFilter(
                    'Rename',
                    array(
                        'target' => $small_img_name,
                        'overwrite' => true
                    ),
                    'small_img'
            );
            if ($upload->receive('small_img')) {
                $uploaded_images['small_img'] = $small_img_name;
            }
        }
        // загрузка большой картинки
        if ($upload->getFileInfo('big_img')) {
            $big_img_name = $this->_basePicsPath . 'big_img/' . $id . '_' . basename($upload->getFileName('big_img'));
            $upload->addFilter(
                    'Rename',
                    array(
                        'target' => $big_img_name,
                        'overwrite' => true
                    ),
                    'big_img'
            );
            if ($upload->receive('big_img')) {
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
        if (!is_dir($this->_basePicsPath . '/small_img')) {
            mkdir($this->_basePicsPath . '/small_img', 0777, true);
        }
        if (!is_dir($this->_basePicsPath . '/big_img')) {
            mkdir($this->_basePicsPath . '/big_img', 0777, true);
        }
        if (!is_dir($this->_basePicsPath . '/thumbs')) {
            mkdir($this->_basePicsPath . '/thumbs', 0777, true);
        }
    }

    public function installAction() {
        //        $view = Zend_Layout::getMvcInstance()->getView();
        //        $view->jQuery->addJavascriptFile('/js/jquery-1.4.2.min')
        //                     ->addJavascriptFile('/js/jquery-ui-1.8.6.custom.min.js');
        require_once 'ArticlesInstall.php';
        echo 'Begin';
        $install = new Articles_Admin_ArticlesInstall('articles');
        // $install->Uninstall();
        $install->Install();
    }

}