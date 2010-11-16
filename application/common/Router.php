<?php

class Router
{
	/**
	 * @var Router
	 */
	protected static $_instance = null;
	
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
	public function deleteRoute($name, $id){
		
		$filename = Configurator::getConfigRoutesFileName();
		$content = file_get_contents($filename);
		
		$first_pos = strpos($content, ";[$name-$id]");
		
		if(!$first_pos)
			return true;
			
		$last_pos = strpos($content, ";[", $first_pos + 1);
		$lenght = $last_pos - $first_pos;
		
		if($last_pos){
			$lenght = $last_pos - $first_pos;
			$content = substr_replace($content, '', $first_pos, $lenght);
		}
		else{
			$content = substr_replace($content, '', $first_pos);
		}
			
		file_put_contents($filename, $content);
		
		return true;
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
	public function replaceRoute($data, $action = 'index', $controller = 'page', $module = 'default'){
		Loader::loadPublicModel('Pages');
		$old = Pages::getInstance()->getPage($data['id']);
		$this->deleteRoute($old->name, $old->id);
		
		if($this->addRoute($data, $action, $controller, $module)){
			return true;
		}
			
		return false;
		
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
		$filename = Configurator::getConfigRoutesFileName();
		//print_r($data);
		$id = $data['id'];
		$name = $data['name'];
		$route = $this->filtered($data['path']);
		$version = $this->filtered($data['lang']);
		$module = $this->filtered($module);
		$action = $this->filtered($action);
		$controller = $this->filtered($controller);
		if($module == 'default' && $controller == 'index'){
			$controller = 'page';
		}
		$string = "\n;[$name-$id]\n";
		$string .= "routes.$id.type = \"Zend_Controller_Router_Route\"\n";
		
		if($version != 'ru'){
			$route = ($route == '') ? "$version" : "$version/$route";
		}
		//$str=$module!='default' ? '/*' :'';
		if($route!=''){
			$route.='/*';
		}
		$string .= "routes.$id.route = \"$route\" \n";
		$string .= "routes.$id.defaults.module = \"$module\"\n";
		$string .= "routes.$id.defaults.controller = \"$controller\"\n";
		$string .= "routes.$id.defaults.action = \"$action\"\n";
		$string .= "routes.$id.defaults.id = \"$id\" \n";
		
		$pre = file_get_contents($filename);
		file_put_contents($filename, $pre . $string);
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
}
