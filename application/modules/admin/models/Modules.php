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
        /**
         * Получает модули находящиеся в директории приложения
         *
         * @param <type> $app_path
         * @return <type> $array
         */
        private function GetModulesInApp($app_path)
        {
            $di = new DirectoryIterator($app_path.'/modules/');
      
            foreach ($di as $dc)
            {
              if(($dc->isDir()) && (!$dc->isDot()))
              $modules[] =$dc->getFilename();
            }
              return $modules;     
        }
        /**
         * Возвращает все модули присутствующие в системе
         * @param boolean $is_array = false
         * @return mixed 
         */
        public function GetModulesInSystem($is_array = false)
        {
            $select = $this->select();
            $select->order('name ASC');
            $result = $this->fetchAll($select);
            
            return $result = $is_array ? $result->toArray(): $result;
        }
        /**
         *
         * @param string $module_name 
         */
        private function AddModule($module_name)
        {
            require_once APPLICATION_PATH.'/library/Ext/Common/InstallModuleAbstract.php';
            echo "$module_name<br/>";
            $config_module = new Ext_Common_Config($module_name, 'config');
            $data = array(
            'name'  => $config_module->module->sys->name,
            'module_ver' => $config_module->module->version,    
            'title' => $config_module->module->name,
            'describe' => $config_module->module->describe,
            'add_in_sys' =>  new Zend_Db_Expr('NOW()'),
            'priority' => 0,
            'active' => 1,
            'installed' => 0    
            );
            $this->insert($data);
        }
        /**
         *
         * @param string $module_name 
         */
        private function DeleteModule($module_name)
        {
           $where = $this->getAdapter()->quoteInto('name = ?', $module_name);
           $this->delete($where);
        }
        

        /**
         *
         * @return object Zend_Db_Table
         */
        public function ModulesSync()
        {
            $modulesApp = $this->GetModulesInApp(APPLICATION_PATH);
            $modulesSys = $this->GetModulesInSystem(true);
            
            for ($i=0; $i < count($modulesSys); $i++) $modulesNameSys[$i] = $modulesSys[$i]['name'];
            $modulesNameSys = $modulesNameSys == NULL ? array() : $modulesNameSys;
            
            $new_modules = array_diff($modulesApp, $modulesNameSys);
            print_r($new_modules);
            echo var_dump($modulesNameSys);
            foreach ($new_modules as $new_module) $this->AddModule ($new_module);
            
            $old_modules = array_diff($modulesNameSys, $modulesApp);
            print_r($old_modules);
            foreach ($old_modules as $old_module) $this->DeleteModule ($old_module);
            
            return $this->GetModulesInSystem();
        }
	
	
}