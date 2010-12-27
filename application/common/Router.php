<?php

class Router {
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
	public static function getInstance() {
		if (null === self::$_instance) {
			self::$_instance = new self();
			self::$_instance->init();
		}
		
		return self::$_instance;
	}
	
	protected function init() {
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
	public function addRoute($data, $action = 'index', $controller = 'page', $module = 'default') {
		if (! $this->hasRoute( $data ['name'], $data ['id'] )) {
			$this->write( $data, $action, $controller, $module );
			
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
	public function deleteRoute($name) {
		
		$config = new Zend_Config_Yaml( $this->_routeFileName, null, true );
		$config->__unset( $name );
		
		$writer = new Zend_Config_Writer_Yaml();
		$writer->setFilename( $this->_routeFileName );
		$writer->setConfig( $config );
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
	public function replaceRoute($data, $action = 'index', $controller = 'page', $module = 'default') {
		
		$route_name = $data ['path'];
		
		if (! $this->hasRoute( $route_name )) {
			return $this->addRoute( $data, $action, $controller, $module );
		}
		
		$config = new Zend_Config_Yaml( $this->_routeFileName, null, true );
		
		$config->routes->routes->$route_name->type = "Zend_Controller_Router_Route";
		$config->routes->routes->$route_name->route = $route_name;
		$config->routes->routes->$route_name->defaults->module = $module;
		$config->routes->routes->$route_name->defaults->controller = $controller;
		$config->routes->routes->$route_name->defaults->action = $action;
		$config->routes->routes->$route_name->defaults->id = $data ['id'];
		
		$writer = new Zend_Config_Writer_Yaml();
		$writer->setFilename( $this->_routeFileName );
		$writer->setConfig( $config );
		$writer->write();
	
	}
	
	/**
	 * Проверка существования роута в файле
	 *
	 * @param string $name
	 * @param int $id
	 * @return boolean
	 */
	public function hasRoute($name) {
		$config = new Zend_Config_Yaml( $this->_routeFileName );
		return $config->routes->routes->__isset( $name );
	
	}
	
	/**
	 * Запись роута в файл
	 *
	 * @param array $data
	 * @param string $action
	 * @param string $controller
	 * @param string $module
	 */
	private function write($data, $action, $controller, $module) {
		
		$route_name = $data['path'];
		
		$config = new Zend_Config_Yaml( $this->_routeFileName, null, true );
		
		$config->routes->routes->$route_name = array ();
		$config->routes->routes->$route_name->__set( 'type', "Zend_Controller_Router_Route" );
		$config->routes->routes->$route_name->__set( 'route', $route_name );
		$config->routes->routes->$route_name->defaults = array ();
		$config->routes->routes->$route_name->defaults->__set( 'module', $module );
		$config->routes->routes->$route_name->defaults->__set( 'controller', $controller );
		$config->routes->routes->$route_name->defaults->__set( 'action', $action );
		$config->routes->routes->$route_name->defaults->__set( 'id', $data ['id'] );
		
		$writer = new Zend_Config_Writer_Yaml();
		$writer->setFilename( $this->_routeFileName );
		$writer->setConfig( $config );
		$writer->write();
	
	}
	
	
}
