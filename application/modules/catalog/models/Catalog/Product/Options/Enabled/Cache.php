<?php
/** 
 * @author Vitali
 * 
 * 
 */
class Catalog_Product_Options_Enabled_Cache extends Zend_Cache {
	
	const CACHE_DIR =  'product_options_enabled';	
	const CACHE_TAG = 'product_options_enabled';
	
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
        	$backendName = 'File';
			$frontendName = 'Class';
			
       		$cache_dir = DIR_DB_CACHE . self::CACHE_DIR;
			if (!is_dir($cache_dir)){
				mkdir($cache_dir, 0755);
			}
			// Устанавливаем массив опций для выбранного фронтэнда		
			$frontendOptions = array(			
			    'cached_entity' 	=> Catalog_Product_Options_Enabled::getInstance(), 	 // экземпляр класса
				'cache_id_prefix'	=>'options_',
				'caching'			=> true,  			
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
     * очиста кеша
     */
    public static function clear(){
    	$cache = self::getInstance();    	
    	$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
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