<?php

require_once('Zend/Db/Table.php');

class Templates {

    protected static $_instance = null;

    private $_systemTemplates =array();

    /**
     * Singleton instance
     *
     * @return Templates
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function init() {
        // скрывает  шаблоны для админки
        $this->_systemTemplates =array(
                //'compare', // сравнение товаров
                'search', // поиск
                'main', // главная
                'sitemap', // Карта сайта
                //  'works_list',  // список работ портфолио
                // 'work', // работа портфолио
                // 'works', // работы юристов
                // 'peoples', // сотрудники
                // 'publications' // публикации
        );
    }

    public function getAll($module = 'default') {
        $this->init();
        chdir($this->getLayoutPath());
        $dir = opendir(".");
        $lauouts = array();
        $modules = array();

        while(($cur = readdir($dir)) != false) {
            $explode = explode('.', $cur);

            if($this->isTemplate($explode)) {
                $comments = $this->getComments($name = $explode[0]);
                if ($comments) {
                    if($this->filtered($comments->module) == $module && !in_array($name,$this->_systemTemplates)) {
                        foreach ($comments as $key => $data) {
                            $modules[$name][$key] = $data;
                        }
                    }
                }
            }
        }

        return $modules;
    }
    public function getTemplateName($system_name, $module='') {
        if (!$module) {
            $module = 'default';
        }
        $comments = $this->getComments($system_name);
        if ($comments) {
            if($this->filtered($comments->module) == $module) {
                foreach ($comments as $key => $data) {
                    $modules[$system_name][$key] = $data;
                }
            }
            return $modules;
        }
    }
    public function getLayoutPath() {
        return DIR_APPLICATION . 'layouts' . DS . 'scripts' . DS;
    }

    public function getCommentsPath() {
        return DIR_APPLICATION . 'layouts' . DS . 'comments' . DS;
    }

    public function getComments($name) {
        $comments = @simplexml_load_file($this->getCommentsPath() . $name .'.xml');

        return $comments;
    }

    public function isTemplate($explode) {
        if(count($explode) == 2 && $explode[1] == 'phtml' && $explode[0] != 'admin')
            return true;

        return false;
    }

    public function filtered($param) {
        $filter = $this->getFilter();
        $param = $filter->filter($param);
        $param = str_replace("\n", '', $param);
        $param = str_replace(" ", '', $param);

        return $param;
    }

    private function getFilter() {
        $filter = new Zend_Filter();
        $filter->addFilter(new Zend_Filter_StripTags())
                ->addFilter(new Zend_Filter_StringTrim());

        return $filter;
    }

}