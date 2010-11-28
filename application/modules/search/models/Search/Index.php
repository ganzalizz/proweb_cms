<?php


class Search_Index extends Zend_Db_Table {

    protected $_name = 'site_search_index';
    protected $_primary = array('id_item', 'type');	
	protected $_sequence = false;
    protected static $_instance = null;
    
    
    
    const TABLE_NAME = 'table';
    const TITLE = 'title';
    const URL = 'url';
    const ID_ITEM = 'id_item';
    const WHERE = 'where';
    const FIELDS = 'fields';
    
    const URL_FIELD = 'url_field';
    const FIELDS_TO_SELECT = 'select_fields';
    
    protected $_options_keys = array(
	     self::TABLE_NAME, // название таблицы в бд
	     self::TITLE, // поле заголовка в таблице    
	     self::URL, // правило формирования ссылки
	     self::ID_ITEM, // id элемента страницы
	     self::WHERE, // условие выборки записей из таблицы
	     self::FIELDS // массив полей для индексации
    
    );
    
	/**
	 * phpMorphy instance
	 * @var phpMorphy
	 */
    protected static $_phpMorphy = null;
    

    /**
     * Singleton instance
     *
     * @return Search_Index
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
     /**
     * Singleton instance
     *
     * @return phpMorphy
     */
    private function getPhpMorphy(){
    	 if (null === self::$_phpMorphy) {
    	 	
    	 	require_once DIR_LIBRARY.'PHPMorphy/src/common.php';
    	 	// set some options
			$opts = array(
			    // storage type, follow types supported
			    // PHPMORPHY_STORAGE_FILE - use file operations(fread, fseek) for dictionary access, this is very slow...
			    // PHPMORPHY_STORAGE_SHM - load dictionary in shared memory(using shmop php extension), this is preferred mode
			    // PHPMORPHY_STORAGE_MEM - load dict to memory each time when phpMorphy intialized, this useful when shmop ext. not activated. Speed same as for PHPMORPHY_STORAGE_SHM type
			    'storage' => PHPMORPHY_STORAGE_FILE,
			    // Enable prediction by suffix
			    'predict_by_suffix' => true, 
			    // Enable prediction by prefix
			    'predict_by_db' => true,
			    // TODO: comment this
			    'graminfo_as_text' => true,
			);
			// путь к словарям
			$dir = DIR_LIBRARY.'PHPMorphy/dicts';
			// текущий язык
			$lang = 'ru_RU';
    	 	
	    	 // Create phpMorphy instance
			try {
			    self::$_phpMorphy = new phpMorphy($dir, $lang, $opts);
			} catch(phpMorphy_Exception $e) {
			    die('Error occured while creating phpMorphy instance: ' . PHP_EOL . $e);
			}			
           
        }

        return self::$_phpMorphy;
    }
    
    /**
     * обработка текста, что бы получить начальную форму слов
     * @param unknown_type $text
     * @return string
     */
    public function procesText($text){
    	$morphy = $this->getPhpMorphy();
    	
	    $bulk_words = $this->textToWords($text);
		$base_form = $morphy->getBaseForm($bulk_words);
		$fullList = array();
		if ( is_array($base_form) && count($base_form) ){
			foreach ( $base_form as $k => $v ){
				if ( is_array($v) ){
					foreach ( $v as $v1 ){
						if ( strlen($v1) > 3 ){
							$fullList[$v1] = 1;
						}
					}		
				}			
			}
			return $words = join(' ', array_keys($fullList));
		}				
	
		
		return '';
    }
    /**
     * индексация таблиц указанных в search.ini
     */
    public function buildIndexes(){
    	$dbAdapter = Zend_Db_Table::getDefaultAdapter();
    	$ini = new Ext_Common_Config('search','tables');    	
    	if (isset($ini) && $ini->count()){
    		foreach ($ini as $table=>$params){
    			
    			
    			$options = $this->setParams($params->toArray());
    			if($options===false){
    				echo  'Оштбка. Не верно уназаны параметры.';
    				continue;
    			} 
    			$dbAdapter->delete($this->_name, $dbAdapter->quoteInto('type = ?', $table));
    			
    			
    			
    			// формируем запрос на выборку данных из таблицы
    			$select = $dbAdapter->select()
    			 ->from($options[self::TABLE_NAME], $options[self::FIELDS_TO_SELECT]);
    			if (isset($options[self::WHERE]) && count($options[self::WHERE])){
    				foreach ($options[self::WHERE] as $field=>$value){
    					$select->where("$field = ?", $value);
    				}
    			}     			
    			$result = $dbAdapter->query($select)->fetchAll();
    			
    			if (count($result)){
    				foreach ($result as $row){
    					$text = '';
    					foreach ($options[self::FIELDS] as $index_field){
    						$text.= $row[$index_field]. ' ';
    					}
    					$data['type'] = $table;
    					$data['id_item'] = $row[self::ID_ITEM];
    					$data['url'] = $this->createUrl($options[self::URL], $options[self::URL_FIELD] , $row[$options[self::URL_FIELD]] );
    					$data['title'] = $row[$options[self::TITLE]];
    					$data['content'] = $this->procesText($text);
    					$data['original_content'] = strip_tags($text); 
    					$this->insert($data);
    					unset($data);
    				}
    			}
    			echo 'обработано '.count($result) .' записей';
    			unset($options);
    			
    		}
    	}
    }
    /**
     * находит название ключевого поля для 
     * формирования ссылки
     * @param string $param
     * @return string |false
     */
    private function getUrlField($param){
    	
    	 if( preg_match('/:([_-\w\d]+)/si', $param, $matches)){
    	 	return $matches[1];    	 	
    	 }
    	 return false;
    	 
    }
    /**
     * 
     * @param string $base_url
     * @param string $field_name
     * @param string $value
     * @return string
     */
    private function createUrl($base_url, $field_name, $value){
    	if ($field_name && $value){
    		return str_replace(":$field_name", $value, $base_url);
    	}
    	return '';
    }
    /**
     * подготовка параметров
     * @param array $params
     * @return array|false
     */
    private function setParams($params){
    	$options = array();    	
    	foreach ($params as $key=>$value){
    		if (in_array($key, $this->_options_keys)){
    			$options[$key] = $value;
    		}
    	}
    	if (isset($options[self::ID_ITEM]) && isset($options[self::TITLE]) && isset($options[self::URL]) 
    		 && isset($options[self::FIELDS])){
			// формируем массив полей для выборки из таблицы
    		$options['select_fields'] = $options[self::FIELDS];
    		$options['select_fields'][self::ID_ITEM] =$options[self::ID_ITEM];	
    		$options['select_fields'][] = $options[self::TITLE];	
    		$options['select_fields'][] = $this->getUrlField($options[self::URL]);
    		$options['url_field'] = $this->getUrlField($options[self::URL]);
    		return $options;	
    	}
    	return false;
    }
    /**
     * 
     * @param unknown_type $words
     * @param unknown_type $page
     * @param unknown_type $item_per_page
     * @param unknown_type $type
     * @return Zend_Paginator
     */
    public function search($words,$page, $item_per_page, $type = null ){
    	$all_forms = $this->getPhpMorphy()->getAllForms($this->textToWords($words));
    	$fullList = array();
    	if ( is_array($all_forms) && count($all_forms) ){
			foreach ( $all_forms as $k => $v ){
				if ( is_array($v) ){
					foreach ( $v as $v1 ){
						if ( strlen($v1) > 3 ){
							$fullList[$v1] = 1;
						}
					}		
				}			
			}
			if (count($fullList)){
				$words = join(' ', array_keys($fullList));
			}
		}				
	
		
    	
    	
    	$select = $this->select();
    	if ($type){
    		$select->where('type = ?', $type);
    	}
    	$select->where('MATCH(content, original_content) AGAINST(?)', $words);
    	
    	$adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
                $paginator = new Zend_Paginator($adapter);
                $paginator->setCurrentPageNumber($page);
         return $paginator->setItemCountPerPage($item_per_page);
    	
    	
    	//echo $select->__toString(); 
    	//return $this->fetchAll($select);
    }
    
    /**
     * чистим текст и разбиваем на слова 
     * @param string $text
     * @return array
     */
    private function textToWords($text){
    	$words = preg_replace('#\[.*\]#isU', '', strip_tags($text));
		$words = preg_replace('/(&.+;)/isU', '', strip_tags($words));
		$words = preg_split('#\s|[,.:;!?"\'()]#', $words, -1, PREG_SPLIT_NO_EMPTY);
   		
		$bulk_words = array();
		
		$filter = new Zend_Filter_StringToUpper();
		$filter->setEncoding('UTF-8');
		foreach ($words as $word){
			if (mb_strlen($word, 'UTF-8')>3){
				$bulk_words[] = $filter->filter($word);
			}
		}
		return $bulk_words;
    }

   
	
   

}