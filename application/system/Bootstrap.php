<?php
//require_once '/../library/Zend/Application/Bootstrap/Bootstrap.php';
//require_once '/../library/Zend/Application/Bootstrap/BootstrapAbstract.php';
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{                      

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
        Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
        Bootstrap::registerGlobals(array(
            'helpersPaths' => Bootstrap::getAllHelpersDirectories(),
            'scriptsPaths' => array_merge(array('.', DIR_LAYOUTS), Bootstrap::getAllHelpersScriptsDirectories()),
            'config' => Bootstrap::getConfigSection()
        ));
//        
//        Loader::loadCommon('controllers/MainAdminController');
//        include_once DIR_COMMON.'Functions.php';
        
        
             
    }
      protected function _initControlerDirectory()
      {
          $front = Zend_Controller_Front::getInstance();
          $front->getDispatcher()->setParam('prefixDefaultModule', true);
      }   


//     protected function _initZFDebug()
//    {
//        
//        $autoloader = Zend_Loader_Autoloader::getInstance();
//        $autoloader->registerNamespace('ZFDebug');
//
//        $options = array(
//            'plugins' => array(
//                'Variables',
//                'File' => array('base_path' => 'd:\\Web\\WWW\\EasyStart\\application'),
//                'Memory',
//                'Time',
//                'Registry',
//                'Exception',
//                'Html',
//            ),
//            'image_path' => 'd:\\Web\\WWW\\EasyStart\\images\\debugbar',
//            'jquery_path' => 'd:\\Web\\WWW\\EasyStart\\js\\jquery\\jquery.js'
//            
//        );
//         
//        // Настройка плагина для адаптера базы данных
//        if ($this->hasPluginResource('db')) {
//            $this->bootstrap('db');
//            $db = $this->getPluginResource('db')->getDbAdapter();
//            $options['plugins']['Database']['adapter'] = $db;
//        }
//
//        // Настройка плагина для кеша
//        if ($this->hasPluginResource('cache')) {
//            $this->bootstrap('cache');
//            $cache = $this-getPluginResource('cache')->getDbAdapter();
//            $options['plugins']['Cache']['backend'] = $cache->getBackend();
//        }
//
//        $debug = new ZFDebug_Controller_Plugin_Debug($options);
//       
//        $this->bootstrap('frontController');
//        $frontController = $this->getResource('frontController');
//        $frontController->registerPlugin($debug);
//      
//        
//    }
//    
       
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
     * 
     */
    private static function getConfigSection()
    {
       // return $config = new Zend_Config_Ini('/../configuration/configuration.ini', 'config');
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

    // функция замены e-mail адресов
	private static  function email_replace($text)
	{
		$exp = '/([a-zA-Z0-9|.|-|_]{2,256})@([a-zA-Z0-9|.|-]{2,256}).([a-z]{2,4})/';
		return preg_replace($exp, '<script type="text/javascript"> eml = \'\1\' +  "@" + \'\2\'+\'.\3\'; document.write(eml);</script>', $text);
	} 
    
    public static function _initStart() {
        
                Bootstrap::init(); 
		Zend_Session::start();
       // $frontController = Zend_Controller_Front::getInstance()
       //         ->addModuleDirectory(DIR_MODULES)
       //         ->setDefaultModule($module)
       //         ->throwExceptions(true)
       //         ->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());
       
        Configurator::setupDatabase();
        Configurator::setupView(Zend_Registry::get('helpersPaths'), Zend_Registry::get('scriptsPaths'));
        Configurator::setupRoutes(Zend_Controller_Front::getInstance()->getRouter());
        Configurator::tuneEnvironment();
        
        
        
        Security::getInstance()->init();
        SiteAuth::getInstance()->init();
        
        
        
		Zend_Search_Lucene_Analysis_Analyzer::setDefault( new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive( ) );
		Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding( Ext_Search_Lucene::ENCODING );
		
//        $exeption=0;
//        if ($exeption) {
//            try {
//                $frontController->dispatch();
//            }
//            catch (Exception $e)
//            {
//                header('Location: /404');
//                //	exit();
//
//            }
//        } else {
//        	$frontController->dispatch();
//        	//$url = $_SERVER['REQUEST_URI'];
//        	//echo $url;
//        	/*$frontController->returnResponse(true);
//        	$response = $frontController->dispatch();
//        	
//        	//$html = implode('',);
//        	$html = Bootstrap::email_replace($response->getBody());
//        	$response->setBody($html);
//        	$response->sendResponse();*/
//           //echo $frontController->getResponse();
//            
//        }
        
        

    }


}