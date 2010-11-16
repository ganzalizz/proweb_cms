<?php

class Catalog_Product_Images extends Zend_Db_Table {
	
	protected $_name = 'site_catalog_product_images';
	protected $_primary = array('id');
	protected $_sequence = true; // Использование таблицы с автоинкрементным ключом
	protected static $_instance = null;
	
	/**
	 * Class to use for row	
	 *
	 * @var string
	 */
	protected $_rowClass = "Catalog_Product_Images_Row" ;
	
	
	protected $_dependentTables = array(
		
	);
	
	protected $_referenceMap    = array(       
        'tovar'=> array(
            'columns'           => 'id_product',
            'refTableClass'     => 'Catalog_Product',
            'refColumns'        => 'id',
	 		'onDelete'          => self::CASCADE,
        ),
    );
    
    /**
     * действия с элементами
     * @var unknown_type
     */
    private $_options = array(        										
	        'disable'=>' Заблокировать',
	        'enable'=>' Активировать',
	        'delete'=>' Удалить',
        
        );
	
	/**
	 * Singleton instance
	 *
	 * @return Catalog_Product_Images
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * получение опций
	 */
	public function getOptions(){
		return $this->_options;
	}
	
	/**
	 * получать фотографии товара
	 * @param int  $id_product
	 * @param int $count
	 * @param int $offset
	 * @return Zend_Db_Table_Rowset
	 */
	public function getImagesByProductId($id_product, $count = null, $offset = null){
		$select = $this->select();
		$select->where("id_product = ?", (int)$id_product );
		$select->order('priority DESC');
		$select->limit($count, $offset);
		return $this->fetchAll($select);
		
	}
	
	/**
	 * изменение активности
	 * @param int $id
	 */
	public function changeActivity($id){
		$row = $this->find($id)->current();
		if ($row!=null){
			$row->active = abs($row->active-1);
			return  $row->save();
		}
		
	}
	/**
	 * изменение приоритета для нескольких элементов 
	 * @param unknown_type $params
	 */
	public function setProperties($priority, $titles){
		if (is_array($priority)){
			$ids = array_keys($priority);
			$rowset = $this->find($ids);
			foreach ($rowset as $row){
				$row->priority = $priority[$row->id];
				$row->title = isset($titles[$row->id]) ? $titles[$row->id] : '';
				$row->save();
			}
		}
	}
	/**
	 * действия над элементами
	 * @param string $action
	 * @param array $ids
	 */
	public function processImages($action, $ids){				
		$rowset = $this->find($ids);
		foreach ($rowset as $row){
			switch ($action) {
				case 'disable': $row->active = 0; $row->save();
				;
				break;
				case 'enable': $row->active = 1; $row->save();
				;
				break;
				case 'delete': $row->delete();
				;
				break;
				
				
			}
		}
	}
	
	/**
     * Загрузка изображений 
     */
    public function upload_Images($id_product) {       
        foreach ($_FILES as $key=> $file) {
            if ( $file['error']===0 && strpos($file['type'], 'image')!==false && strpos($key, 'image')!==false ) {
                $img_name = $file['name'];
                $img_source = $file['tmp_name'];
                if (isset($img_name) && $img_name!='' && $img_source!='' ) {
                    $ext = @end(explode('.', $img_name));
                    $name = date('Ymd_h_i_s');
                    $img_big = DIR_PUBLIC.'pics/catalog/product/'.$name.'_img.'.$ext;                  
                    if (copy($img_source, $img_big)){                    	
                    	$row = $this->fetchNew();
                    	$row->img = $name.'_img.'.$ext;
                    	$row->title = isset($_POST['title_'.$key]) ? $_POST['title_'.$key] : '';
                    	$row->id_product = $id_product;
                    	$row->active = 1;
                    	$row->save();
                    }
                }
            }
        }

    }
    /**
     * получение главной картинки к товару
     * @param int $id_product
     */
    public function getMainByProduct($id_product){
    	$select = $this->select();
    	$select->where("id_product = ?", $id_product);
    	$select->where("main = ?", 1);
    	$select->where("active = ?", 1);
    	return $this->fetchRow($select);    	
    	
    }
    /**
     * удаление главной картинки товара
     * @param unknown_type $id_product
     */
    public function deleteMainByProduct($id_product){
    	$select = $this->select();
    	$select->where("id_product = ?", $id_product);
    	$select->where("main = ?", 1);    	
    	$row = $this->fetchRow($select);
    	if ($row!=null){
    		$row->delete();
    	}  
    }
    
    public function deleteByProduct($id_product){
    	$select = $this->select();
    	$select->where("id_product = ?", (int)$id_product);
    	$rowset = $this->fetchAll($select);
    	if ($rowset->count()){
    		foreach ($rowset as $row){
    			$row->delete();
    		}
    	}
    }
    
	
	
}