<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{                      
	/**
	 * @todo вынести пути к хелперам и 
	 * скриптам хелперов в плагин
	 */
    public function init() {
        Bootstrap::defineDirectoriesConstants();
        Bootstrap::setIncludePath(array(
            '.',
            ROOT_DIR,
            DIR_LIBRARY,
            DIR_ZEND,
            DIR_PEAR,
            DIR_DEFAULT_CONTROLLERS,
            DIR_ADMIN_CONTROLLERS,
            DIR_COMMON,
            DIR_APPLICATION,
            DIR_ADMIN_MODELS
            ));
       require_once(ROOT_DIR . 'application/library/Zend/Loader.php');
       require_once(ROOT_DIR . 'application/library/Zend/Loader/Autoloader.php');
       
       /**
        * временный вариант для того чтоб работала адинка
        * @
        */
       //TODO: в дальнейшем предлагаю перенести этот контроллер в EXT
       require_once (ROOT_DIR.'application/common/controllers/MainAdminController.php');
        Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
        Bootstrap::registerGlobals(array(
            'helpersPaths' => Bootstrap::getAllHelpersDirectories(), 
            'scriptsPaths' => array_merge(array('.', DIR_LAYOUTS), Bootstrap::getAllHelpersScriptsDirectories())
            

        ));
      
             
    }

     

       
    /**
     * Определение констант с путями к используемым директориям
     */
    public static function defineDirectoriesConstants() {
        define('SP','/') ;
        define('DS', DIRECTORY_SEPARATOR);
        define('DIR_APPLICATION', ROOT_DIR . 'application' . DS);
        define('DIR_PUBLIC', ROOT_DIR );
        define('DIR_MODULES', ROOT_DIR . 'application' . DS . 'modules' . DS);
        define('DIR_DEFAULT_CONTROLLERS', DIR_MODULES  . 'default' . DS . 'controllers' . DS);
        define('DIR_ADMIN_CONTROLLERS', DIR_MODULES  . 'admin' . DS . 'controllers' . DS);
        define('DIR_LIBRARY', DIR_APPLICATION . 'library' . DS);
        define('DIR_ZEND', DIR_LIBRARY . 'Zend' . DS);
        define('DIR_PEAR', DIR_LIBRARY . 'Pear' . DS);
        define('DIR_COMMON', DIR_APPLICATION . 'common' .  DS);
        define('DIR_MODELS', DIR_APPLICATION . 'models' . DS);
        define('DIR_ADMIN_MODELS', DIR_MODULES . 'admin' . DS. 'models' . DS);
        define('DIR_LAYOUTS', DIR_APPLICATION . 'layouts'.DS.'scripts' . DS);
        define('DIR_DB_CACHE', ROOT_DIR . 'cache_db'. DS);
    }

    /**
     * Append to existing 'include path' array of paths
     *
     * @param array $paths
     */
    public static function setIncludePath($paths) {
        $pathString = implode($paths, PATH_SEPARATOR);
        set_include_path($pathString . get_include_path());
    }

    /**
     * Регистрирует переменные для глобального использования
     *
     * @param array $globals массив пар $index=>value для Zend_registry
     */
    private static function registerGlobals($globals = array()) {
        foreach($globals as $index=>$value) {
            Zend_Registry::set($index, $value);
        }
    } 
    
   /**
     * Нахождение директорий всех существующих модулей
     *
     * @return array
     */
    public static function getAllModulesDirectories() {
        $modules = array();
        $dir = opendir(DIR_MODULES);
        while(($cur = readdir($dir)) != false) {
            if($cur!='.' && $cur!='..' && is_dir(DIR_MODULES.$cur)) {
                if (file_exists(DIR_MODULES.$cur . DS . 'comments.xml') && is_dir(DIR_MODULES . $cur. DS . 'models')) {
                    $modules[] = DIR_MODULES . $cur. DS . 'models' . DS;
                }
            }
        }
        return $modules;
    }
    
  


    /**
     * Нахождение директорий всех существующих помошников вида
     *
     * @return array массив путей к помошникам видов
     */
    private static function getAllHelpersDirectories() {
        $helpersPaths = array();
        $helpersPaths[] = DIR_LIBRARY . 'Ext' . DS . 'View' . DS . 'Helper' . DS;
        $dir = opendir(DIR_MODULES);
        while(($cur = readdir($dir)) != false) {
            if($cur!='.' && $cur!='..' && is_dir(DIR_MODULES.$cur)) {
                if (is_dir(DIR_MODULES . $cur. DS . 'views'.DS.'helpers')) {
                    $helpersPaths[] = DIR_MODULES . $cur. DS . 'views' . DS.'helpers'.DS;
                }
            }
        }
        
        return $helpersPaths;
    }

    /**
     * Нахождение директорий всех существующих представлений помошников вида
     *
     * @return array массив путей к скриптам помошников видов
     */
    private static function getAllHelpersScriptsDirectories() {
        $scriptsPaths = array();
        $dir = opendir(DIR_MODULES);
        while(($cur = readdir($dir)) != false) {
            if($cur!='.' && $cur!='..' && is_dir(DIR_MODULES.$cur)) {
                if (is_dir(DIR_MODULES . $cur. DS . 'views'.DS.'scripts')) {
                    $scriptsPaths[] = DIR_MODULES . $cur. DS . 'views' . DS.'scripts'.DS;
                }
            }
        }
        return $scriptsPaths;
    }

    
    
    public static function _initStart() {
        //Zend_Controller_Front::getInstance()
        	//->registerPlugin(new Zend_Controller_Plugin_ErrorHandler())
        	//->throwExceptions(true);
        //Zend_Controller_Front::getInstance()->getRequest()->set
        Bootstrap::init(); 
		Zend_Session::start();    
        Configurator::initConfig();
        
        Configurator::setupDatabase();
        Configurator::setupView(Zend_Registry::get('helpersPaths'), Zend_Registry::get('scriptsPaths'));
        
        $view = Zend_Layout::getMvcInstance()->getView();   
        $view->addHelperPath(DIR_LIBRARY."ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
        $view->jQuery()->addStylesheet('/css/ui-lightness/jquery-ui-1.8.6.custom.css')
       // ->addStylesheet('/css/ajaxFileUploader/ajaxfileupload.css')        
        ->setLocalPath('/js/jquery-1.4.2.min.js')
        ->setUiLocalPath('/js/jquery-ui-1.8.6.custom.min.js');
        //->addJavascriptFile('/js/jquery.jqUploader.js')
        //->addJavascriptFile('/js/jquery.flash.js');
        Configurator::setupRoutes(Zend_Controller_Front::getInstance()->getRouter());
        


        Configurator::tuneEnvironment(); 
        
        Security::getInstance()->init();
        SiteAuth::getInstance()->init();  
        
		
        
        

    }


}