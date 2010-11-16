<?php
/**
 * Catalog_Tovar
 *
 */
class Catalog_Product extends Zend_Db_Table {
	
	
	
	
	/**
	 * The default table name.
	 *
	 * @var string
	 */
	protected $_name = 'site_catalog_product';
	
	/**
	 * The default primary key.
	 *
	 * @var array
	 */
	protected $_primary = array('id');
	
	/**
	 * Whether to use Autoincrement primary key.
	 *
	 * @var boolean
	 */
	protected $_sequence = true; // Использование таблицы с автоинкрементным ключом
	
	/**
	 * Singleton instance.
	 *
	 * @var St_Model_Layout_Pages
	 */
	protected static $_instance = null;
	
	/**
	 * Dependent tables.
	 *
	 * @var array
	 */
	protected $_dependentTables = array(
		
		'Catalog_Product_Images'
		
	) ;

	/**
	 * Reference map.
	 *
	 * @var array
	 */
	protected $_referenceMap = array(
		'Catalog_Division' => array(
            'columns'           => array('id_division'),
            'refTableClass'     => 'Catalog_Division',
            'refColumns'        => array('id'),
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::RESTRICT
        )
	) ;

	
	/**
	 * Class to use for rows.
	 * Class to use for rows.
	 *
	 * @var string
	 */
	protected $_rowClass = "Catalog_Product_Row" ;
	
	/**
	 * Class to use for row sets.
	 *
	 * @var string
	 */
	protected $_rowsetClass = "Catalog_Product_Rowset" ;
	
	
	
	
	/**
	 * Singleton instance
	 *
	 * @return Catalog_Product
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	/**
	 * backend
	 * получение товаров раздела
	 * @param int  $id_div
	 * @param int $count
	 * @param int $offset
	 * @return Catalog_Product_Rowset
	 */
	public function getProductsByDivId($id_div = null, $count = null, $offset = null){
		$select = $this->select();
		if ($id_div){
			$select->where("id_division = ?", $id_div);
		}
		$select->order("priority DESC");
		$select->limit($count, $offset);
		return $this->fetchAll($select);
	}
	/**
	 * frontend
	 * @param int $id_div
	 * @param int $count
	 * @param int $offset
	 * @return Catalog_Product_Rowset
	 */
	public function getPublicProductsByDivId($id_div, $count = null, $offset = null){
		$select = $this->select();	
		$select->setIntegrityCheck(false);
		$select->from(array('p'=>$this->_name));
		$select->joinLeft(
			array('i'=>'site_catalog_product_images'),
			'p.id=i.id_product AND i.main=1',
			'img'
		);		
		$select->where("p.id_division = ?", $id_div);
		
		$select->where("p.active = ?", 1);
		$select->order("p.priority DESC");
		$select->limit($count, $offset);
		return $this->fetchAll($select);
	}
	/**
	 * опубликованный товар
	 * @param int $id
	 * @return Catalog_Product_Row
	 */
	public function getPublicItem($id){
		$select = $this->select();	
		$select->setIntegrityCheck(false);
		$select->from(array('p'=>$this->_name));
		$select->joinLeft(
			array('i'=>'site_catalog_product_images'),
			'p.id=i.id_product AND i.main=1', 'img');	
		$select->where("p.id = ?", $id);
		$select->where("p.active = ?", 1);
		return $this->fetchRow($select);
	}
	/**
	 * количество опубликованных товаров в разделе
	 * @param int $id_division
	 * @return int
	 */
	public function getCountPublicInDiv($id_division){
		$select = $this->select();
		$select->from($this->_name, 'COUNT(*) AS count');
		$select->where("id_division = ?", (int)$id_division);
		$select->where("active = ?", 1);
		return $this->getAdapter()->fetchOne($select);
	}
	
	/**
	 * изменение активности
	 * @param int $id
	 */
	public function changeActivity($id){
		$row = $this->find($id)->current();
		$row->active = abs($row->active-1);
		return  $row->save();
	}
	/**
	 * изменение приоритета для нескольких товаров
	 * @param unknown_type $params
	 */
	public function setPriority($params){
		$ids = array_keys($params);
		$rowset = $this->find($ids);
		foreach ($rowset as $row){
			$row->priority = $params[$row->id];
			$row->save();
		}
	}
	/**
	 * действия над товарами
	 * @param string $action
	 * @param array $ids
	 */
	public function processProducts($action, $ids){				
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
	 * Добавление элемента 
	 *
	 * @param array $data
	 *	
	 */
	
	public function addProduct($data){
	
		if (!isset($data['created_at'])){
			$data['created_at'] = new Zend_Db_Expr('NOW()');
		}
		$data['prior']=$this->getMaxPriorInDivision($data['id_division'])+1;
		return $this->insert($data);
		
	}
	
	/**
	 * проверить есть в разделе с переданным id  товары или нет 
	 *
	 * @param int $id_division
	 * @return	boolean
	 */	
	public function issetProductInDiv($id){
		$where = $this->getAdapter()->quoteInto('id_division = ?', $id);
		
		$res= $this->fetchRow($where);
		if ($res!=null){
			return true;
		}
		return false;
	}
	
	/**
	 * получить все товары раздела 
	 *
	 * @param int $id_division
	 * @return	Catalog_Product_Rowset
	 */
	
	public function getAllProductsByDivId($id_division,$offset=0, $limit=null,$sort=null){
		//$dbAdapter = Zend_Registry::get('db');
		
		$sortText='';
		if ($sort == 'nASC') {
			$sortText = ' name ASC ';
			
		} elseif ($sort == 'nDESC') {
			$sortText = ' name DESC ';
		}
		
		if ($sort == 'pASC') {
			$sortText = ' price ASC ';
			
		} elseif ($sort == 'pDESC') {
			$sortText = ' price DESC ';
		}
		
		if ($sort==null){
			$sortText = '  prior DESC';
		}
		$where = $this->getAdapter()->quoteInto('id_division = ?', $id_division);
		
		return $this->fetchAll($where,$sortText,$limit,$offset);
		
	}
	
	/**
	 * количество товаров в разделе
	 * @param int $id_division
	 * @return int
	 */
	public function getCountByDivId($id_division){
		$select = $this->select();
		$select->from($this->_name, 'COUNT(*) as count');
		$select->where("id_division = ?", (int)$id_division);
		return $this->getAdapter()->fetchOne($select);
	}
	
	
	
	public function getProductByPrior($prior,$div_id){
		$where = array ($this->getAdapter()->quoteInto('prior = ?', $prior),
						$this->getAdapter()->quoteInto('id_division = ?', $div_id));
		$res =  $this->fetchRow($where);
		if ($res==null) {
			return false; 
		} else {
			return true;
		}
	}
	
	/**
	 * получение стоимости товара
	 * @param int $id_product
	 * @return int
	 */
	public function getPrice($id_product){
		$select = $this->select();
		$select->where("id = ?", (int)$id_product);
		$row = $this->fetchRow($select);
		if ($row!=null){
			return $row->price;
		}
		return '';
	}

        public function reindex(){
		$index = New Ext_Search_Lucene(Ext_Search_Lucene::CATALOG_PRODUCTS, true);
		$count = 10 ;
		$offset = 0 ;
		$path = SiteDivisionsType::getInstance()->getPagePathBySystemName('division');
		while( ( $rowset = $this->fetchAll( null, null, $count, $offset ) ) && ( 0 < $rowset->count() ) ) {
			while( $rowset->valid() ) {
				$row = $rowset->current() ;
				$doc = new Ext_Search_Lucene_Document();
				$doc->setUrl($path.'/product/'.$row->id);
				$doc->setTitle($row->title);
				$doc->setId($row->id);
                $doc->setContent(strip_tags($row->description));

		        $index->addDocument( $doc ) ;

				$rowset->next() ;
			}
			$offset += $count ;
		}

		$index->commit() ;
		return $index->numDocs();

	}

        public function getPopular() {
            $select = $this->select();
            $select->setIntegrityCheck(false);
            $select->from(array('p'=>$this->_name));
            $select->joinLeft(
                    array('i'=>'site_catalog_product_images'),
                    'p.id=i.id_product',
                    'img'
            );
            $select->where("p.popular = ?", 1);
            return $this->fetchAll($select);
        }
	
	
}