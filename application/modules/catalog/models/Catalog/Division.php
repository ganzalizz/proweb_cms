<?php
/**
 * Catalog_Division 
 *
 */

class Catalog_Division extends Zend_Db_Table {
	
		
	/**
	 * The default table name.
	 *
	 * @var string
	 */
	protected $_name = 'site_catalog_division';
	
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
	protected $_sequence = true; // Ис;льзование таблицы с автоинкрементным ключом
	
	/**
	 * Singleton instance.
	 *
	 * @var Catalog_Division
	 */
	protected static $_instance = null;
	
	/**
	 * Dependent tables.
	 *
	 * @var array
	 */
	protected $_dependentTables = array(
		'Catalog_Product'
		
	) ;

	/**
	 * Reference map.
	 *
	 * @var array
	 */
	protected $_referenceMap = array(
	) ;

	
	/**
	 * Class to use for rows.
	 *
	 * @var string
	 */
	protected $_rowClass = "Catalog_Division_Row" ;
	
	/**
	 * Class to use for row sets.
	 *
	 * @var string
	 */
	protected $_rowsetClass = "Catalog_Division_Rowset" ;
	
	/**
	 * Необходима для рекурсивного сохранения
	 * дерева
	 *
	 * @var array
	 */
	private $_tree = null;
	/**
	 * необходима для построения меню каталога
	 *
	 * @var array 
	 */
	private $_div = null;
	
	private $_page_path = null;
		
		
	/**
	 * Singleton instance
	 *
	 * @return catalog_division
	 */
	public static function getInstance(){
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	
	
	/**
	 * 
	 * @param $id
	 * @return Catalog_Division_Row
	 */
	public function getItem($id){
		return $this->find($id)->current();
		
	}
	
	
	/**
	 * Построение дерева для Меню каталога
	 *
	 * 
	 * @param int $parentId
	 * @param int $level
	 * @param int $id_page
	 * @return html
	 */
	public function getCatalogMenu($parentId = 0, $level = 0, $id_current = null, $show_ids){
		
		if (!$this->_page_path){
			$this->_page_path = SiteDivisionsType::getInstance()->getPagePathBySystemName('division');
			
		}
		
		$select = $this->select();
		$select->where("level = ?", (int)$level);
		$select->where("parent_id = ?", (int)$parentId);
		//$select->where("active = ?", 1);
		$select->order('sortId');		
		$nodes = $this->fetchAll ( $select );
		$html = '';
		if ($nodes->count()){
			$show = in_array($parentId, $show_ids)?  "class='show'" : '';
			$html = "<ul $show >";
			foreach ( $nodes as $data ) {
				$current = $id_current==$data->id ? "class='active'" : '';
				if ($this->getCountOfChildren ( $data->id ) > 0 && in_array($data->id, $show_ids)) {
					
					$html.="
						<li id=\"$data->id\"  >							
							<a href=\"/$this->_page_path/division/$data->id\" $current >$data->name</a>";
					$html.=$this->getCatalogMenu (  $data->id, $data->level + 1, $id_current, $show_ids );
					$html.="</li>";
					
				} else {					
					$html.="
						<li id=\"$data->id\"  >							
							<a href=\"/$this->_page_path/division/$data->id\" $current  >$data->name</a>";
					$html.="</li>";
				}
			}	
			$html.='</ul>';	
			return $html;
		}
	}
	
	
	/**
	 * Построение дерева для jqueryTree
	 *
	 * @param string $version
	 * @param int $parentId
	 * @param int $level
	 * @return html
	 */
	public function getTree( $parentId = 0, $level = 0, $version = null) {
		$return = array ( );
		$nodes = array ( );
		$where = array ( 
					$this->getAdapter ()->quoteInto ( 'level = ?', $level ), 
					$this->getAdapter ()->quoteInto ( 'parent_id = ?', $parentId ) 
		);
		$nodes = $this->fetchAll ( $where, 'sortId' );
		$html = '';
		if ($nodes->count()){
			$html = '<ul>';
			foreach ( $nodes as $data ) {
				$isset_products = (int)$data->issetProductInDiv();
				$isset_childs = (int)$data->issetChilds();
				if ($this->getCountOfChildren ( $data->id ) > 0) {
					
					
					
					$html.="
						<li id=\"$data->id\" products=\"$isset_products\" childs=\"$isset_childs\">
							<ins>&nbsp;</ins>
							<a href=\"#\"  >$data->name</a>";
					$html.=$this->getTree (  $data->id, $data->level + 1, $version );
					$html.="</li>";
					
				} else {					
					$html.="
						<li id=\"$data->id\" products=\"$isset_products\" childs=\"$isset_childs\" >
							<ins>&nbsp;</ins>
							<a href=\"#\"   >$data->name</a>";
					if ($isset_products){
						$html.= " <span class='products_count'>($data->products_count)</span>";
					}
					
					$html.="</li>";
				}
			}	
			$html.='</ul>';	
			return $html;
		}
	}
	
	
	
	/**
	 * Сохранение результата dnd 
	 * в зависимости от типа возвращаемого ExtJS
	 * результата
	 *
	 * @param int $id
	 * @param int $parentId
	 * @param string $point
	 */
	public function replace($id, $parentId, $point) {
		/*if ($point == 'above')
			$this->replaceAbove ( $id, $parentId, $point ); elseif ($point == 'below')
			$this->replaceBelow ( $id, $parentId, $point ); elseif ($point == 'append')
			$this->replaceAppend ( $id, $parentId, $point );*/		
		if ($point == 'before')
			$this->replaceAbove ( $id, $parentId, $point ); elseif ($point == 'after')
			$this->replaceBelow ( $id, $parentId, $point ); elseif ($point == 'inside')
			$this->replaceAppend ( $id, $parentId, $point );
			
		$this->addLevel($id);	
	}
	
/**
	 * рекурсивное обновление уровня вложенности при переносе
	 *
	 * @param unknown_type $id
	 */
	private function addLevel($id) {
        $db = $this->getAdapter ();

        $all = $this->fetchAll("parent_id = $id");
        $parentRow = $this->fetchRow("id = $id");

        $this->update(array('level' => $parentRow->level+1), "parent_id = $id");
        
        foreach($all as $value) {
            $this->addLevel($value->id);
        }
    }
	
	/**
	 * Проверка действительности события dnd
	 *
	 * @param int $id
	 * @param int $parentId
	 * @return boolean
	 */
	public function isReplace($id, $parentId){
		$where = $this->getAdapter()->quoteInto('id = ?', $id);
		$node = $this->fetchRow($where);
		
		return $node->parentId == $parentId ? false : true;
	}
	
	
	
	
	
	/**
	 * Получение раздела по ID
	 *
	 * @param int $id
	 *	
	 */
	
	public function getItemById($id){
		$where = $this->getAdapter()->quoteInto('id = ?', $id);
		return $this->fetchRow($where);
		
	}
	
	/**
	 * Получение родительских элементов каталога (parent_id = 0)
	 *
	 * @param int $parent_id
	 *	
	 */
	
	public function getMainDivisions($parent_id=0){
		$where = $this->getAdapter()->quoteInto('parent_id = ?', $parent_id);
		return $this->fetchRow($where);
		
	}
	
	public function getParentDivision($parent_id=0){
		$where = $this->getAdapter()->quoteInto('parent_id = ?', $parent_id);
		return $this->fetchRow($where);
		
	}

    
	
	
	
/**
	 * Перемещение страницы через dnd,
	 * при котором страница выше находится 
	 * на том же уровне
	 * 	 
	 * @param int $id
	 * @param int $parentId
	 * @param string $point
	 */
	private function replaceAbove($id, $parentId, $point) {
		//$page = $this->getPage ( $id );
		$parent = $this->find ( $parentId )->current();
		$this->getAdapter ()->query ( 
			"UPDATE $this->_name 
				SET parent_id = :parent_id, 
					level = :level, 
					sortid = sortid + 1 
				WHERE parent_id = :id AND 
					sortid >= :sort",
			array (
				'parent_id' => $parent->parent_id, 
				'level' => $parent->level, 
				'id' => $parent->parent_id, 
				'sort' => $parent->sortid 
			) 
		);
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$this->update ( 
			array (
				'parent_id' => $parent->parent_id, 
				'level' => $parent->level, 
				'sortid' => $parent->sortid//, 
				//'path' => $this->generateStringPath ( $parent->parent_id, '/' ) . $page->path 
			),
			
			 $where );
	}
	
	/**
	 * Перемещение страницы через dnd,
	 * при котором страница выше является родительской
	 *
	 * @param int $id
	 * @param int $parent_id
	 * @param string $point
	 */
	private function replaceBelow($id, $parent_id, $point) {
		//$page = $this->getPage ( $id );
		$parent = $this->find ( $parent_id )->current();
		
		$this->getAdapter ()->query ( 
			"UPDATE $this->_name 
			SET parent_id = :parent_id, 
				level = :level, 
				sortid = sortid + 1 
			WHERE parent_id = :id AND 
				sortid > :sort",
			 array (
			 	'parent_id' => $parent->parent_id, 
			 	'level' => $parent->level, 
			 	'id' => $parent->parent_id, 
			 	'sort' => $parent->sortid 
			 )
		);
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$this->update ( array (
						'parent_id' => $parent->parent_id, 
						'level' => $parent->level, 
						'sortid' => $parent->sortid + 1//, 
						//'path' => $this->generateStringPath ( $parent->parent_id, '/' ) . $page->path
		 ), $where );
	}
	
	/**
	 * Перемещение страницы через dnd, при котором
	 * она была брошена на страницу, тем самым была добавлена
	 * в конец списка вложенных
	 *
	 * @param int $id
	 * @param int $parent_id
	 * @param string $point
	 */
	private function replaceAppend($id, $parent_id, $point) {
		$item = $this->find($id)->current();
		$parent = $this->find ( $parent_id )->current();
		$max_sort = $this->getMaxSort ( $parent_id );
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$this->update ( array (
			'parent_id' => $parent_id, 'level' => $parent->level + 1, //на 1 больше родительского уровня
			'sortid' => $max_sort + 1, //на 1 больше максимального- добавляется в конец
			/*'path' => $this->generateStringPath ( $parent->id, '/' ) . $page->path*/ )
		, $where );
	}
	
	/**
	 * Получение максимального sortid 
	 * родительской страницы
	 *
	 * @param int $id
	 * @param int $id_page
	 * @return int
	 * 
	 */
	public function getMaxSort( $id){
		return $this->getSort( $id, 'DESC');
	}
	
	public function getMainDiv($id){
		$data= $this->getItemById($id);
		while ($data->parent_id!=0){
			$data=$this->getItemById($data->parent_id);
		}
		
		return $data->id;
	}
	
	
	
	
	/**
	 * обновление счетчика товаров для раздела
	 * @param int $id_division
	 */
	public function updateCountProducts($id_division){
		$count = Catalog_Product::getInstance()->getCountByDivId($id_division);
		$data = array('products_count'=>(int)$count);
		$where = $this->getAdapter()->quoteInto("id = ?", $id_division);
		$this->update($data, $where);
	}
	
	/**
	 * Получение sortid 
	 * родительской страницы,
	 * определенным образом сортируя(asc, desc)
	 *
	 * @param int $id
	 * @return int
	 */
	private function getSort( $id, $type){
		$select = $this->select();
		$select->order("sortid $type");
		$select->where('parent_id = ?', $id);
		$item = $this->fetchRow($select);
		
		return $item ? $item->sortid : 0;
	}

    /**
	 * Создание копии раздела вместе с поддеревом
	 *
	 * @param int $id
	 */
	public function copyDivisionWithChildren($id, $parentId = 0, $deep = 0) {
		$division = $this->getDivision ( $id );
		$parentId = $this->addDivision ( $division, $parentId, $deep );

        $meta = $this->getMetaDivision($id);
        $meta = $meta->toArray();
        unset($meta['id']);
        $meta['item_id'] = $parentId;
        $this->editMetaDivision($meta, $parentId);

        $deep++;
		
		foreach ( $this->getChildren ( $division->id ) as $key => $data ) {
			$this->copyDivisionWithChildren ( $data, $parentId, $deep );
		}
	}

    
    	

	

    /**
	 * Получение количествва дочерних элементов
	 *
	 * @param int $id
	 * @return int
	 */
	public function getCountOfChildren($id) {
		$select = $this->select();
		$select->from($this->_name, 'COUNT(*) as count');
		$select->where('parent_id = ?', $id);
		return $this->getAdapter()->fetchOne($select);
	}

    /**
	 * Получение дочерних элементов
	 *
	 * @param int $id
	 * @return array
	 */
	public function getChildren($id) {
		$where = $this->getAdapter()->quoteInto ( 'parent_id = ?', $id );
		$nodes = $this->fetchAll ( $where );

		$array = array ( );

		foreach ( $nodes as $key => $data ) {
			$array [$data->name] = $data->id;
		}

		return $array;
	}
	
	/**
	 * получение корневых разделов каталога
	 * @param int $count
	 * @param int $offset
	 * @return Catalog_Division_Rowset
	 */
	public function getRootItems($count = null, $offset = null){
		$select = $this->select();
		$select->where("parent_id = ?", 0);
		$select->where("level = ?", 0);
		$select->order("sortid");
		$select->limit($count, $offset);
		return $this->fetchAll($select);
		
	}
	
	

    /**
	 * Получение максимального ID
	 *
	 * @return unknown
	 */
	private function getMaxId() {
		$max = $this->fetchRow ( null, 'id DESC' );

		return $max->id;
	}

    /**
	 * Получение количества копий раздела
	 * для определения префикса новой копии
	 *
	 * @param string $name
	 * @return int
	 */
	private function getCountOfCopies($name) {
		$db = $this->getAdapter ();
		$sql = $db->quoteInto ( "SELECT count(*) as c FROM $this->_name WHERE name regexp ?", $name . '_[[:digit:]]' );

		$result = $db->query ( $sql );
		$count = $result->fetchAll ();

		return $count [0] ['c'];
	}
	
	/**
	 * переиндексация элементов для поиска
	 */
    public function reindex(){
		$index = New Ext_Search_Lucene(Ext_Search_Lucene::CATALOG_DIVISIONS, true);
		$count = 10 ;
		$offset = 0 ;
		$path = SiteDivisionsType::getInstance()->getPagePathBySystemName('division');
		while( ( $rowset = $this->fetchAll( null, null, $count, $offset ) ) && ( 0 < $rowset->count() ) ) {
			while( $rowset->valid() ) {
				$row = $rowset->current() ;
				$doc = new Ext_Search_Lucene_Document();
				$doc->setUrl($path.'/division/'.$row->id);
				$doc->setTitle($row->name);
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

  }