<?php

/**
 * Performs app configuration steps
 *
 */
class Configurator {

    /**
     * Singleton instance.
     *
     * @var St_Model_Layout_Pages
     */
    protected static $_instance = null;

    /**
     * Zend_Cache object
     *
     * @var object
     */
    protected $_cache = null;

    /**
     * Singleton instance
     *
     * @return Configurator
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self ( );
        }
        return self::$_instance;
    }


    /**
     * Установка соединения с БД
     *
     */
    public static function setupDatabase() {
        $config = Configurator::getConfig('db');
        $db = Zend_Db::factory($config->db->type, $config->db->config->toArray());
        $sql = "SET NAMES UTF8";
        $db->query($sql);
        $db->getProfiler()->setEnabled(true);
        Zend_Db_Table::setDefaultAdapter($db);
        Zend_Registry::set('db', $db);
    }

    /**
     * Установка парамаетров View
     *
     * array $helpersPaths массив путей к помощникам видов
     * array $scriptsPaths массив путей к представлениям помощников видов
     */
    public static function setupView($helpersPaths, $scriptsPaths) {
        $view = new Zend_View();
        $view->strictVars(false);
        $view->addScriptPath($scriptsPaths);
        $view->addHelperPath($helpersPaths , 'View_Helper') ;
        $view->addHelperPath(array(DIR_LIBRARY. 'Ext'. DS . 'View' . DS . 'Helper' . DS), 'Ext_View_Helper');
        $view->setEncoding('UTF-8');
        $config['view'] = $view;
        $config['layoutPath'] = $scriptsPaths;
        Zend_Layout::startMvc($config);
        
        Zend_Registry::set('view', $view);
    }

    /**
     * Уставновка роутов
     *
     * @param object $router
     */
    public static function setupRoutes($router) {
        $routes = Configurator::getRoute('routes');
        //echo var_dump($routes);
        $router->addConfig($routes, 'routes');
    }

    /**
     * Получение блока конфигурации
     * из ini-файла
     *
     * @param string $blockName
     * @return unknown
     */
    public static function getConfig($blockName) {
        $file_name = APPLICATION_PATH . '/configuration/configuration.ini';
        return new Zend_Config_Ini($file_name, $blockName);
    }

    /**
     * Получение имени файла конфигурации
     *
     * @return string
     */
    public static function getConfigFileName() {
        return DIR_APPLICATION . 'configuration/configuration.ini';
    }

    /**
     * Получение конфигурации роутов
     *
     * @param string $name
     * @return object
     */
    public static function getRoute($name) {
        return  new Zend_Config_Yaml(APPLICATION_PATH . '/configuration/routes.yml', $name);
        //print_r($config->toArray());
    }

    /**
     * Получение имени файла роутов
     *
     * @return string
     */
    public static function getConfigRoutesFileName() {
        $file_name = DIR_APPLICATION . 'configuration/routes.yml';
        return $file_name;
    }


    /**
     * инициализация кэша
     *
     * @return Zend_Cache
     */
    public function getCache() {
        if (!is_null($this->_cache)) {
            return $this->_cache;
        } else {
            $backendName = 'File';
            $frontendName = 'Output';
            // Устанавливаем массив опций для выбранного фронтэнда
            $frontendOptions = array();
            // Устанавливаем массив опций для выбранного бэкэнда
            $backendOptions = array('cache_dir'=>DIR_PUBLIC.'cache'. DS);
            $this->_cache = Zend_Cache::factory($frontendName,
                    $backendName,
                    $frontendOptions,
                    $backendOptions);
            return $this->_cache;
        }
    }

    /**
     * Настраивает параметры PHP и другие
     */
    public static function tuneEnvironment() {
        date_default_timezone_set('Europe/Minsk');
        setlocale(LC_TIME, 'ru_RU.UTF-8');
        ini_set('magic_quotes_gpc', 0);
        error_reporting(E_ALL|E_STRICT);
    }
    /**
     * добавляем в реестр разделы из configuration.ini
     */
    public static function initConfig(){
    	$config = self::getConfig('config');
    	Zend_Registry::set('config', $config);
    }
}