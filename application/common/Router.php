<?php

class Router
{
	/**
	 * @var Router
	 */
	protected static $_instance = null;
        
        /**
         *
         * @var route.ini path with filename 
         */
        protected $_routeFileName = null;
	
	/**
	 * Singleton instance
	 * @return Router
	 */
	public static function getInstance(){
    		if (null === self::$_instance) {
        			self::$_instance = new self();
    		}

    		return self::$_instance;
	}
		
	public function init(){
		Loader::loadCommon('Configurator');
                $this->_routeFileName = Configurator::getConfigRoutesFileName();
	}
	
	/**
	 * Добавление нового роута в файл
	 * конфигурации
	 *
	 * @param array $data
	 * @param string $action
	 * @param string $controller
	 * @param string $module
	 * @return boolean
	 */
	public function addRoute($data, $action = 'index', $controller = 'page', $module = 'default'){
		if(!$this->hasRoute($data['name'], $data['id']))
		{
			$this->write($data, $action, $controller, $module);
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Удаление роута из файла
	 * конфигурации
	 *
	 * @param string $name
	 * @param int $id
	 * @return boolean
	 */
	public function deleteRoute($name)
        {
		                 
          $config = new Zend_Config_Ini($this->_routeFileName, null, true);
          $config->__unset($name);
          
          $writer = new Zend_Config_Writer_Ini();
          $writer->setFilename($this->_routeFileName);
          $writer->setConfig($config);
          $writer->write();
          
		
	}
	
	/**
	 * Замена роута в файле
	 * 
	 * @param array $data
	 * @param string $action
	 * @param string $controller
	 * @param string $module
	 * @return string
	 */
	public function replaceRoute($data, $action = 'index', $controller = 'page', $module = 'default')
        {
	        
                $route_name = $this->filtered($data['path']);
                
                $config = new Zend_Config_Ini($this->_routeFileName, null, true);
                
		$config->$route_name->routes->$route_name->type = "Zend_Controller_Router_Route";
                $config->$route_name->routes->$route_name->route = $route_name;
                $config->$route_name->routes->$route_name->defaults->module = $module;
                $controller = (($module == 'default') && ($controller == 'index')) ? 'page' : $controller ;
                $config->$route_name->routes->$route_name->defaults->controller = $controller;
                $config->$route_name->routes->$route_name->defaults->action = $action;
                $config->$route_name->routes->$route_name->defaults->id = $data['id'];
                
                $writer = new Zend_Config_Writer_Ini();
                $writer->setFilename($this->_routeFileName);
                $writer->setConfig($config);
                $writer->write();	
		
	}
	
	/**
	 * Проверка существования роута в файле
	 *
	 * @param string $name
	 * @param int $id
	 * @return boolean
	 */
	public function hasRoute($name, $id){
		$filename = Configurator::getConfigRoutesFileName();
		$content = file_get_contents($filename);
		
		if(strstr($content, "\n;[$name-$id]")){
			return true;
		}
		
		return false;
	}
	
	/**
	 * Запись роута в файл
	 *
	 * @param array $data
	 * @param string $action
	 * @param string $controller
	 * @param string $module
	 */
	private function write($data, $action, $controller, $module){
            
                
                $route_name = $this->filtered($data['path']);
                
                $config = new Zend_Config_Ini($this->_routeFileName, null, true);
                
		$config->$route_name = array();
                $config->$route_name->__set('routes.'.$route_name.'.type', "Zend_Controller_Router_Route");
                $config->$route_name->__set('routes.'.$route_name.'.route', $route_name);
                $config->$route_name->__set('routes.'.$route_name.'.defaults.module', $module);
                $controller = (($module == 'default') && ($controller == 'index')) ? 'page' : $controller ;
                $config->$route_name->__set('routes.'.$route_name.'.defaults.controller', $controller);
                $config->$route_name->__set('routes.'.$route_name.'.defaults.action', $action);
                $config->$route_name->__set('routes.'.$route_name.'.defaults.id', $data['id']);
                
                $writer = new Zend_Config_Writer_Ini();
                $writer->setFilename($this->_routeFileName);
                $writer->setConfig($config);
                $writer->write();
		

    }
    
    /**
     * Получение объекта фильтра
     * для очистки от '/n' и ' '  при чтении
     * из файла 
     *
     * @return object
     */
    private function getFilter(){
    	$filter = new Zend_Filter();
		$filter->addFilter(new Zend_Filter_StripTags())
			->addFilter(new Zend_Filter_StringTrim());
			
		return $filter;
    }
    
    /**
     * Применение фильтра
     *
     * @param string $param
     * @return string
     */
    private function filtered($param){
    	$filter = $this->getFilter();
    	$param = $filter->filter($param);
    	$param = str_replace("\n", '', $param);
    	$param = str_replace(" ", '', $param);
    	
    	return $param;
    }
    
    private function DoRouteName($param)
    {
      $matches = null;  
      preg_match('/[a-z,_,-]+/', $param, $matches);
      return $maches[0];
    }
}
