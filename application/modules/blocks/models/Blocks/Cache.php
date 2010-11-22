<?php
/** 
 * @author Vitali
 * 
 * 
 */
class Blocks_Cache extends Zend_Cache {
	
	const CACHE_DIR =  'blocks';	
	const CACHE_TAG = 'blocks';
	
	/**
	 * 
	 * @var Zend_Cache_Frontend_Class
	 */
	protected static $_cache = null;
	
	/**
     * Singleton instance
     *
     * @return Zend_Cache_Frontend_Class
     */
    public static function getInstance() {
        if (null === self::$_cache) {
        	
        	$config = Zend_Registry::get('config');
        	$allow_cache = (boolean)$config->cache->siteblocks;
        	
        	
        	$backendName = 'File';
			$frontendName = 'Class';
			
       		$cache_dir = DIR_DB_CACHE . self::CACHE_DIR;
			if (!is_dir($cache_dir)){
				mkdir($cache_dir, 0755);
			}
			// Устанавливаем массив опций для выбранного фронтэнда		
			$frontendOptions = array(			
			    'cached_entity' 	=> Blocks::getInstance(), 	 // экземпляр класса
				'cache_id_prefix'	=>'blocks_',
				'caching'			=> $allow_cache,  			
			);
			// Устанавливаем массив опций для выбранного бэкэнда			
			$backendOptions = array ('cache_dir' => $cache_dir );		
			$cache = parent::factory($frontendName, $backendName, $frontendOptions, $backendOptions);
			$cache->setTagsArray(array(self::CACHE_TAG));
            self::$_cache = $cache;
        }

        return self::$_cache;
    }
	
	
	
	/**
	 * удаление кэша
	 */
	public static function clean(){
		$cache = self::getInstance();
		$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
	}
}

?>