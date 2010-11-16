<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Ext_Resource_IncludeMH extends Zend_Application_Resource_ResourceAbstract
{
    
    protected $_modelDirectoryName = 'models';
    
   
    
    public function getModelDirectoryName()
    {
        return $this->_modelDirectoryName;
    }
    
     /**
     * @var array
     */
    protected $_params = array ();
    
    /**
     * Set plugin options
     *
     * @param array $params
     */
    public function setParams(array $params) {
        $this->_params = $params;
    }
    
    /**
     * Return plugin options
     *
     * @return array
     */
    public function getParams() {
        return $this->_params;
    }
    
     /**
     * @var array
     */
    protected $_modelDirs = array();
    
     /**
     * @var array
     */
    protected $_options = array();
    
    
    
     public function init()
    {
       $this->getModelsDirectory(); 
    }
    
    
    
    private function getModelsDirectory()
    {
        $this->_options = $this->getParams();
        
        if (isset($this->_options['path'])) $path = $this->_options['path'];
              
        try{
            $dir = new DirectoryIterator($path);
        } catch(Exception $e) {
            require_once 'Zend/Controller/Exception.php';
            throw new Zend_Controller_Exception("Directory $path not readable", 0, $e);
        }
        foreach ($dir as $file) {
            if ($file->isDot() || !$file->isDir()) {
                continue;
            }

            $module    = $file->getFilename();

            // Don't use SCCS directories as modules
            if (preg_match('/^[^a-z]/i', $module) || ('CVS' == $module)) {
                continue;
            }

            $this->_modelDirs[] = $file->getPathname() . DIRECTORY_SEPARATOR . $this->getModelDirectoryName(). DIRECTORY_SEPARATOR;
           
        }
        
         $this->setIncludePath($this->_modelDirs);
        
        
    }
    
    protected function setIncludePath($paths) {
        $pathString = implode($paths, PATH_SEPARATOR);
        set_include_path($pathString . get_include_path());
        
    }
}


