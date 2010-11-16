<?php

class Modules extends Zend_Db_Table 
{
	protected $_name = 'site_modules';
	protected $_primary = array('id');
	protected static $_instance = null;
	protected $_path = DIR_MODULES;

	/**
	 * Singleton instance
	 *
	 * @return Modules
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	/**
	 * Нахождение всех существующих моулей
	 *
	 * @return array
	 */
	public function getAllModules(){
		$modules = array();
		$dir = $this->getDir();
		
		while(($cur = readdir($dir)) != false)
		{
			if($this->isModule($cur)){
                if ('.svn' == $cur) continue;
				$comments = $this->getComments($cur);	
				$comments['name']= $cur;
				$modules[] = $comments;	
			}
		}
		
		return $modules;
	}
	
	/**
	 * Получение пути к каталогу модулей
	 *
	 * @return string
	 */
	public function getPath(){
		return $this->_path;
	}
	
	/** Проверяет соответствие на папку с модулем
	 *
	 * @param descriptor
	 * @return boolean
	 */
	private function isModule($cur)
	{
		if($cur == '.' || $cur == '..')
			return false;
			
		if(!is_dir($cur))
			return false;
			
		if($cur == 'admin')	
			return false;
			
		return true;	
	}

	/**
	 * Получение коммнтов к модулю
	 *
	 * @param string$name
	 * @return object
	 */
	public function getComments($name){		
		$comments = new Zend_Config_Xml($this->getPath() . $name . DS . 'comments.xml');				
		//$comments = simplexml_load_file($this->getPath() . $name . DS . 'comments.xml');		
		return $comments->toArray();
	}
	
	/**
	 * Получение директории моделуй
	 *
	 * @return object
	 */
	private function getDir(){
		chdir($this->getPath());
		$dir = opendir(".");
		
		return $dir;
	}
	
	
}