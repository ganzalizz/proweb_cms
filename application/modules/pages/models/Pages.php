<?php

class Pages extends Zend_Db_Table {
	
	/**
	 * The default table name.
	 *
	 * @var string
	 */
	protected $_name = 'site_content';
	
	/**
	 * The default primary key.
	 *
	 * @var array
	 */
	protected $_primary = array ('id' );
	
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
	 * Class to use for rows.
	 *
	 * @var string
	 */
	protected $_rowClass = "Page_Row";
	
	/**
	 * Class to use for row sets.
	 *
	 * @var string
	 */
	protected $_rowsetClass = "Page_Rowset";
	
	/**
	 * Dependent tables.
	 *
	 * @var array
	 */
	protected $_dependentTables = array(
		'Menu',
		'News',
		'PagesOptions'
	) ;

	/**
	 * Reference map.
	 *
	 * @var array
	 */
	protected $_referenceMap = array(
		
	) ;
	
	/**
	 * Необходима для рекурсивного сохранения
	 * дерева
	 *
	 * @var array
	 */
	private $_tree = null;
	
	/**
	 * Необходима для рекурсивного сохранения
	 * пути
	 *
	 * @var array
	 */
	private $_path = array ( );
	
	/**
	 * Singleton instance
	 *
	 * @return Pages
	 */
	public static function getInstance() {
		if (null === self::$_instance) {
			self::$_instance = new self ( );
		}
		
		return self::$_instance;
	}
	
	
	
	/**
	 * Получение сраницы определенной языковой версии
	 *
	 * @param array $data
	 * @return object
	 */
	public function getVersionPage($data) {
		$where = array ($this->getAdapter ()->quoteInto ( 'path = ?', $data->path ) );
		$page = $this->fetchRow ( $where );
		
		return $page;
	}
	
	/**
	 * Получение страницы
	 *
	 * @param int $id
	 * @param string $order
	 * @return Zend_Db_Table_Row
	 */
	public function getPage($id, $order = null) {
		$select = $this->select()
			->where('is_active = ?', 1)
			->where('id = ?', (int)$id);
			if ($order!=null){
				$select->order($order);
			}
		return $this->fetchRow($select);
	}
	
	/**
	 * Нахождение страницы новой языковой
	 * версии, соответствующей определенной русской
	 *
	 * @param int $id
	 * @param string $version
	 * @return object
	 */
	public function getConformParent($id, $version) {
		if ($id == 0) {
			return null;
		}
		
		$page = $this->getPage ( $id );
		$where = array ($this->getAdapter ()->quoteInto ( 'level = ?', $page->level ), $this->getAdapter ()->quoteInto ( 'priority = ?', $page->priority ), $this->getAdapter ()->quoteInto ( 'version = ?', $version ) );
		$conform = $this->fetchRow ( $where );
		
		return $conform;
	}
	
	/**
	 * Нахождение страниц по переданному параметру
	 *
	 * @param string $name
	 * @param string $value
	 * @return array
	 */
	public function getPagesByParam($name, $value) {
		$where = $this->getAdapter ()->quoteInto ( "$name = ?", $value );
		
		return $this->fetchAll ( $where );
	}
	
	/**
	 * Нахождение страницы по переданному параметру
	 *
	 * @param string $name
	 * @param string $value
	 * @return Zend_Db_Table_Row
	 */
	public function getPageByParam($name, $value) {			
		$select = $this->select()
			->where("$name = ?", $value);	
		return $this->fetchRow ( $select );
	}
	
	/**
	 * Нахождение страниц определенной
	 * языковой версии
	 *
	 * @param string $lang
	 * @param string $module
	 * @return array
	 */
	public function getVersionPages($lang, $module) {
		$where = array ($this->getAdapter ()->quoteInto ( "module = ?", $module ) );
		
		return $this->fetchAll ( $where );
	}
	
	/**
	 * Построение дерева для ExtJS
	 *
	 * @param string $version
	 * @param int $id_parent
	 * @param int $level
	 * @return array
	 */
	public function getTree($id_parent = null) {
		$return = array ( );
		$nodes = array ( );
		
		$select = $this->select();	
		if (is_null($id_parent)){
			$select->where('ISNULL(id_parent)', null);
		} else {
			$select->where('id_parent = ?', (string)$id_parent);	
		}	
		$select->where('deleted = ?', 0);
		$select->order('priority');
		
		$nodes = $this->fetchAll ( $select );
		$html = '';
		if ($nodes->count()){
			$html = '<ul>';
			foreach ( $nodes as $data ) {
				
				if ($this->getCountOfChildren ( $data->id ) > 0) {
					$html.="<li id=\"$data->id\" ><div class=\"all\"><ins>&nbsp;</ins><a href=\"#\" class=\"tree_item_name\">$data->title</a><div class=\"controls\">".$this->getDuration ( $data )."</div></div>";
					$html.=$this->getTree ($data->id );
					$html.="</li>";
					//$return [] = array ('task' => $data->title, 'duration' => $this->getDuration ( $data ), 'user' => Security::getInstance ()->getUser ()->username, 'id' => $data->id, 'uiProvider' => 'col', 'cls' => 'master-task', 'iconCls' => 'task-folder', 'children' => $this->getTree ( $version, $data->id, $data->level + 1 ) );
				} else {
					//$return [] = array ('task' => $data->title, 'duration' => $this->getDuration ( $data ), 'user' => Security::getInstance ()->getUser ()->username, 'id' => $data->id, 'uiProvider' => 'col', 'leaf' => 'true', 'iconCls' => 'task' );
					$html.="<li id=\"$data->id\"><div class=\"all\" ><ins>&nbsp;</ins><a href=\"#\" class=\"tree_item_name\">$data->title</a><div class=\"controls\">".$this->getDuration ( $data )."</div></div>";
					
					$html.="</li>";
				}
			}	
			$html.='</ul>';	
			return $html;
		}
	}
	
/**
	 * Построение дерева для ExtJS
	 *
	 * @param string $version
	 * @param int $id_parent
	 * @param int $level
	 * @return array
	 */
	public function getSitemap($version, $id_parent = 1, $level = 1) {
		$select = $this->select()
			->reset()
			->where('level = ?', $level)
			->where( 'id_parent = ?', $id_parent)
			->where('deleted = ?', 0)
			->where('show_in_sitemap = ?', 1)
			->order('priority');
		
		
		
		$nodes = $this->fetchAll ( $select );
		$html = '';
		if ($nodes->count()){
			$html = '<ul>';
			foreach ( $nodes as $data ) {
				
				if ($this->hasChild($data->id )) {
					$html.="<li id=\"$data->id\" ><a href=\"/$data->path\" >$data->title</a>";
					$html.=$this->getSitemap ( $version, $data->id, $data->level + 1 );
					$html.="</li>";
					
				} else {					
					$html.="<li id=\"$data->id\" ><a href=\"/$data->path\" >$data->title</a>";
					
					$html.="</li>";
				}
			}	
			$html.='</ul>';	
			return $html;
		}
	}
	
	/**
	 * рекурсивное обновление уровня вложенности при переносе
	 *
	 * @param unknown_type $id
	 */
	private function addLevel($id) {
        $db = $this->getAdapter ();

        $all = $this->fetchAll("id_parent = $id");
        $parentRow = $this->fetchRow("id = $id");

        $this->update(array('level' => $parentRow->level+1), "id_parent = $id");
        
        foreach($all as $value) {
            $this->addLevel($value->id);
        }
    }
	
	/**
	 * Сохранение результата dnd 
	 * в зависимости от типа возвращаемого ExtJS
	 * результата
	 *
	 * @param int $id
	 * @param int $id_parent
	 * @param string $point
	 */
	public function replace($id, $id_parent, $point) {
		/*if ($point == 'above')
			$this->replaceAbove ( $id, $id_parent, $point ); elseif ($point == 'below')
			$this->replaceBelow ( $id, $id_parent, $point ); elseif ($point == 'append')
			$this->replaceAppend ( $id, $id_parent, $point );*/
		if ($point == 'before')
			$this->replaceAbove ( $id, $id_parent, $point ); elseif ($point == 'after')
			$this->replaceBelow ( $id, $id_parent, $point ); elseif ($point == 'inside')
			$this->replaceAppend ( $id, $id_parent, $point );
			
		$this->addLevel($id);	
	}
	
	/**
	 * Проверка действительности события dnd
	 *
	 * @param int $id
	 * @param int $id_parent
	 * @return boolean
	 */
	public function isReplace($id, $id_parent) {
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$node = $this->fetchRow ( $where );
		
		return $node->id_parent == $id_parent ? false : true;
	}
	
	/**
	 * Получение корневого элемента
	 * определенной языковой версии
	 *
	 * @param string $lang
	 * @return object
	 */
	public function getRoot($lang = 'ru') {
		$select = $this->select()
			->where('ISNULL(id_parent)', null);
		//$where = array ($this->getAdapter ()->quoteInto ( 'type = ?', 'root' ), $this->getAdapter ()->quoteInto ( 'version = ?', $lang ) );
		
		return $this->fetchRow ( $select );
	}
	
	
	
	/**
	 **Нахождение всех страниц условия
	 *
	 * @param string $where
	 * @param string $order
	 * @return array
	 */
	public function getAll($where, $order = null) {
		return $this->fetchAll ( $where, $order );
	}
	
	/**
	 * Получение количествва дочерних элементов
	 *
	 * @param int $id
	 * @return int
	 */
	public function getCountOfChildren($id) {
		$page = $this->getChildren ( $id );
		
		return count ( $page );
	}
	
	/**
	 * Получение дочерних элементов
	 *
	 * @param int $id
	 * @return array
	 */
	public function getChildren($id) {
		$where = $this->getAdapter ()->quoteInto ( 'id_parent = ?', $id );
		$nodes = $this->fetchAll ( $where );
		
		$array = array ( );
		
		foreach ( $nodes as $key => $data ) {
			$array [$data->title] = $data->id;
		}		
		return $array;
	}

    /**
	 * Получение дочерних элементов и их адресов для меню
	 * TODO нужно изменить незвание метода либо удалить его
	 * @param int $id
	 * @return Zend_Db_Table_Rowset
	 */
	public function getChildrenAndURLs($id) {
		
		$select = $this->select()
			->where('is_active = ?', 1 )
			->where('id_parent = ?', $id)
			->order('priority');
		
		return $this->fetchAll($select);
	}
	
	/**
	 * Добавление новой страницы
	 *
	 * @param array $data
	 * @param string $module
	 * @return int
	 */
	public function addPage($data, $module = 'default') {		
		
		$id=null;
		if (is_array ( $data )) {
			$new_data = $this->getDataPage ( $data, $module );
			$id=$this->createRow()->setFromArray($new_data)->save(); 
			//$this->insert ( $new_data );
			$data ['id'] = $id;
			PagesOptions::getInstance ()->addOptionsPage ( $data );
		} else {
			$original_id = $data->id;
			$new_data = $this->getCopyDataPage ( $data );
			$id = $this->insert ( $new_data );
			PagesOptions::getInstance ()->addOptionsCopyPage ( $original_id, $id );
			Menu::getInstance ()->addVersion ( $original_id, $id );			
			Router::getInstance ()->addRoute ( $this->getPage($id)->toArray() );
		
		}
		
		if (is_array ( $data ) && isset ( $data ['menu'] )) {			
			Menu::getInstance ()->addMenu ( $id, $data ['menu'] );
		}
		
		return $id;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $data
	 */
	public function editPage($data) {		
		$new_data = $this->getUpdateDataPage ( $data );		
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $data ['id'] );
		$page = $this->find($data ['id'])->current();
		$page->setFromArray(array_intersect_key($data, $page->toArray()));
		$page->save();
		//$this->update ( $new_data, $where );
		PagesOptions::getInstance ()->editOptionsPage ( $data );		
		Menu::getInstance ()->editMenu ( $data );
	}
	
	public function deletePage($ids) {
		
		$ids = ( array ) $ids;
		
		$where = $this->getAdapter ()->quoteInto ( "id IN (?)", $ids );
		$this->delete ( $where );
		$this->deleteMenu ( $ids );
		$this->deletePageOptions ( $ids );		
		
		
		
		return true;
	}
	
	
	
	public function deletePageOptions($ids) {
		$where = $this->getAdapter ()->quoteInto ( "pageId IN (?)", $ids );
		//Loader::loadPublicModel ( 'PagesOptions' );
		PagesOptions::getInstance ()->deleteOptions ( $where );
	}
	
	/**
	 * изменение активности
	 * @param int $id
	 */
	public function changeActive($id){
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$data = array('is_active'=>new Zend_Db_Expr('IF(is_active=1,0,1)'));
		return  $this->update($data, $where);
		
	}
	
	public function deleteMenu($ids) {
		$where = $this->getAdapter ()->quoteInto ( "pageId IN (?)", $ids );		
		Menu::getInstance ()->deleteMenu ( $where );
	}
	
	/**
	 * Возвращает страницы с указанными в массиве id
	 *
	 * @param array $ids
	 * @return array
	 */
	public function getPages($ids) {
		$where = $this->getAdapter ()->quoteInto ( "id IN (?)", $ids );
		$result = $this->fetchAll ( $where );
		
		return $result ? $result : array ( );
	}
	
	
	
	
	
	
	public function search($search){
		 $dbAdapter = Zend_Registry::get('db');
		 $sql = $dbAdapter->quoteInto("SELECT title, id, 'pages' as TYPE, path FROM site_content WHERE (site_content.title LIKE '%".$search."%' OR
		 	 site_content.intro LIKE '%".$search."%'
		 	OR site_content.content LIKE '%".$search."%' ) AND (site_content.deleted=0 AND site_content.is_active =1)  ; ",null);
				
		 $result = $dbAdapter->query($sql);
		 return  $result->fetchAll();
		
	}
	
	public function remove($id){
		
		$where = $this->getAdapter()->quoteInto('id = ?', (int)$id);
		return $this->delete($where);
		
		/*$page= $this->find($id)->current();
		if ($this->getCountOfChildren($id)){
			$childs = $this->fetchAll('id_parent='.(int)$id);
			foreach ($childs as $child){
				$this->remove($child->id);
			}
		}
		if (!is_null($page)){
			@unlink(DIR_PUBLIC.'pics/default/'.$page->img);			
			$page->delete();	
		}	*/
	}
	
	
	public function reindex(){
		
		$index = New Ext_Search_Lucene(Ext_Search_Lucene::PAGES, true);		
		$index->setMergeFactor(10000);
		$rowset = $this->fetchAll( 'is_active=1' );
			while( $rowset->valid() ) {				
				$row = $rowset->current() ;
				//
				// Prepare document
				//
				$doc = new Ext_Search_Lucene_Document();
				$doc->setUrl($row->path);
				$doc->setTitle($row->title);
				$doc->setId($row->id);
                $doc->setContent(strip_tags($row->content));
                				
		        $index->addDocument( $doc ) ;

				$rowset->next() ;
			}
		return $index->numDocs();
		
	}
       
	
	/**
	 * проверка уникальности адреса
	 *
	 * @param string $path
	 * @param int $id
	 * @param string $lang
	 * @return bool
	 */
	public function checkPath($path, $id=null, $lang){
		$where = "path='$path'";
		if ($id!=null){
			$where.= " AND id!=".(int)$id;
		}
		$row = $this->fetchRow($where);
		if (!is_null($row)){
			return false;
		}
		return true;
		
	}
	
	
	
	
	/**
	 * Перемещение страницы через dnd,
	 * при котором страница выше находится 
	 * на том же уровне
	 * 	 
	 * @param int $id
	 * @param int $id_parent
	 * @param string $point
	 */
	private function replaceAbove($id, $id_parent, $point) {
		$page = $this->getPage ( $id );
		$parent = $this->getPage ( $id_parent );
		$this->getAdapter ()->query ( "UPDATE $this->_name SET id_parent = :id_parent, level = :level, priority = priority + 1 WHERE id_parent = :id AND priority >= :sort", array ('id_parent' => $parent->id_parent, 'level' => $parent->level, 'id' => $parent->id_parent, 'sort' => $parent->priority ) );
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$this->update ( 
			array (
				'id_parent' => $parent->id_parent, 
				'level' => $parent->level, 
				'priority' => $parent->priority//, 
				//'path' => $this->generateStringPath ( $parent->id_parent, '/' ) . $page->path 
			),
			
			 $where );
	}
	
	/**
	 * Перемещение страницы через dnd,
	 * при котором страница выше является родительской
	 *
	 * @param int $id
	 * @param int $id_parent
	 * @param string $point
	 */
	private function replaceBelow($id, $id_parent, $point) {
		$page = $this->getPage ( $id );
		$parent = $this->getPage ( $id_parent );
		
		$this->getAdapter ()->query ( "UPDATE $this->_name SET id_parent = :id_parent, level = :level, priority = priority + 1 WHERE id_parent = :id AND priority > :sort", array ('id_parent' => $parent->id_parent, 'level' => $parent->level, 'id' => $parent->id_parent, 'sort' => $parent->priority ) );
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$this->update ( array (
						'id_parent' => $parent->id_parent, 
						'level' => $parent->level, 
						'priority' => $parent->priority + 1//, 
						//'path' => $this->generateStringPath ( $parent->id_parent, '/' ) . $page->path
		 ), $where );
	}
	
	/**
	 * Перемещение страницы через dnd, при котором
	 * она была брошена на страницу, тем самым была добавлена
	 * в конец списка вложенных
	 *
	 * @param int $id
	 * @param int $id_parent
	 * @param string $point
	 */
	private function replaceAppend($id, $id_parent, $point) {
		$page = $this->getPage ( $id );
		$parent = $this->getPage ( $id_parent );
		$max_sort = $this->getMaxSort ( $id_parent );
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$this->update ( array (
			'id_parent' => $id_parent, 'level' => $parent->level + 1, //на 1 больше родительского уровня
			'priority' => $max_sort + 1, //на 1 больше максимального- добавляется в конец
			/*'path' => $this->generateStringPath ( $parent->id, '/' ) . $page->path*/ )
		, $where );
	}
	
	/**
	 * Получение уровня страницы
	 *
	 * @param int $id
	 * @return int
	 */
	private function getLevel($id) {
		$page = $this->getPage ( $id );
		
		return $page->level;
	}
	
	/**
	 * Получение минимального priority 
	 * родительской страницы
	 *
	 * @param int $id
	 * @return int
	 */
	private function getMinSort($id) {
		return $this->getSort ( $id, 'ASC' );
	}
	
	/**
	 * Получение максимального priority 
	 * родительской страницы
	 *
	 * @param int $id
	 * @return int
	 */
	private function getMaxSort($id) {
		return $this->getSort ( $id, 'DESC' );
	}
	
	/**
	 * Получение priority 
	 * родительской страницы,
	 * определенным образом сортируя(asc, desc)
	 *
	 * @param int $id
	 * @return int
	 */
	private function getSort($id, $type) {
		$order = "priority $type";
		$where = $this->getAdapter ()->quoteInto ( 'id_parent = ?', $id );
		$page = $this->fetchRow ( $where, $order );
		
		return $page ? $page->priority : 0;
	}
	
	/**
	 * Проверка на родительство
	 *
	 * @param int $id
	 * @return boolean
	 */
	private function hasChild($id) {
		try {
			$where = $this->getAdapter ()->quoteInto ( 'id_parent = ?', $id );
			$root = $this->fetchRow ( $where );
		} catch ( Exception $e ) {
			echo $e->getMessage ();
			exit ();
		}
		
		return $root ? true : false;
	}
	
	
	
	
	
	/**
	 * Вбивание данных при добавлении новой страницы
	 *
	 * @param array
	 * @return array
	 */
	private function getDataPage($data, $module) {
		$user = Security::getInstance ()->getUser ();
		
		$parent = $this->getPage ( ( int ) $data ['parent_id'] );
		$countOfChildren = $this->getCountOfChildren ( $parent->id );
		//$maxId = $this->getMaxId ();
		$url = isset ( $data ['path'] ) ? $data ['path'] : '';
		if (isset ( $data['id_div_type'] ) && $data['id_div_type']!=0){
			$type = SiteDivisionsType::getInstance()->find($data['id_div_type'])->current();
			if ($type!=null){
				$data['inside_items'] = $type->go_to_module;
			}
		}
		$result = array (
			//'id' => $maxId + 1,
			'type' => 'page',
			//'version' => isset ( $data ['lang'] ) ? $data ['lang'] : $parent->version,
			'is_active' => isset ( $data ['is_active'] ) ? '1' : '0',
			'pubDate' => date ( "Y-m-d H:i:s" ),
			'sitemap' => isset ( $data ['sitemap'] ) ? '1' : '0',
			'show_childs' => isset ( $data ['show_childs'] ) ? '1' : '0',
			'unpubDate' => date ( "Y-m-d H:i:s" ),
			'intro' => isset($data['intro']) ? $data['intro'] : '',
			'content' => isset ( $data ['content'] ) ? $data ['content'] : '',
			'template' => isset ( $data ['template'] ) ? $data ['template'] : 'default',
			'module' => $module, 'createdBy' => $user->id,
			'editedBy' => $user->id,
			'allow_delete'=>1,
			'deleted' => '0',
			'deletedBy' => $user->id,
			'is_activeBy' => $user->id,
			'id_parent' => isset ( $data ['parent_id'] ) ? $data ['parent_id'] : '1',
			'level' => $parent->level + 1,
			'priority' => $countOfChildren + 1,
			'title' => isset ( $data ['title'] ) ? $data ['title'] : '',
			'inside_items' => isset ( $data ['inside_items'] ) ? $data ['inside_items'] : '0',
			'id_div_type' => isset ( $data ['id_div_type'] ) ? $data ['id_div_type'] : '0',			
			'path' => $data ['path'] );
		
		return $result;
	}
	
	/**
	 * Получение данных только что измененной страницы
	 *
	 * @param array $data
	 * @return array
	 */
	private function getUpdateDataPage($data) {
		$user = Security::getInstance ()->getUser ();
		if (isset ( $data['id_div_type'] ) && $data['id_div_type']!=0){
			$type = SiteDivisionsType::getInstance()->find($data['id_div_type'])->current();
			if ($type!=null){
				$data['inside_items'] = $type->go_to_module;
			}
		}		
		$result = array (
			'is_active' => isset ( $data ['is_active'] ) ? (int)$data ['is_active'] : '0',
			'sitemap' => isset ( $data ['sitemap'] ) ? (int)$data ['sitemap'] : '0',
			'id_div_type' => isset ( $data ['id_div_type'] ) ? $data ['id_div_type'] : '0',
			'show_childs' => isset ( $data ['show_childs'] ) ? (int)$data ['show_childs'] : '0',
			'intro' => isset($data['intro']) ? $data['intro'] : '',
			'content' => isset ( $data ['content'] ) ? $data ['content'] : '',
			'template' => isset ( $data ['template'] ) ? $data ['template'] : 'default',
			'editedBy' => $user->id,
			'title' => isset ( $data ['title'] ) ? $data ['title'] : '',
			'inside_items' => isset ( $data ['inside_items'] ) ? $data ['inside_items'] : '0',
			'path' => $data ['path'] );
		
		return $result;
	}
	
	
	
	/**
	 * Получение количества копий страницы
	 * для определения префикса новой копии
	 *
	 * @param string $title
	 * @return int
	 */
	private function getCountOfCopies($title) {
		$db = $this->getAdapter ();
		$sql = $db->quoteInto ( "SELECT count(*) as c FROM $this->_name WHERE title regexp ?", $title . '_[[:digit:]]' );
		
		$result = $db->query ( $sql );
		$count = $result->fetchAll ();
		
		return $count [0] ['c'];
	}
	
	
	
	/**
	 * Получение текста для вставки иконок управления 
	 * напротив каждого элемента
	 *
	 * @param array $data
	 * @return string
	 */
	private function getDuration($data) {
		$title = 'Выключить';		
		if ($data->is_active == '0') {			
			$title = 'Включить';
		}
		$lang = 'ru';
		if ($data->id_parent!=''){
			//$module = ($data->module == 'default' || empty ( $data->module )) ? 'pages' : $data->module;
			
			$url =  $data->path;
			$delete = $data->allow_delete==1 ? "<a href ='#' title='Удалить' delete=\"true\" id_page=\"$data->id\" ><img src='/img/admin/delete.png' /></a>" : '';
			//$copy = $data->allow_delete==1 ? "<a href ='#' title='Сделать копию' ><img src='/images/plus_b.gif' onclick='javascript:window.location = \"/pages/$lang/admin_pages/copy/id/$data->id/\" '/></a>" :'';
			
			
			//$go_to_module = $data->inside_items ==1 ? "<a href ='#' title='Перейти внутрь раздела' ><img src='/img/admin/folder.gif' onclick='javascript:window.location = \"/pages/$lang/admin_pages/gotomodule/id_page/$data->id/id_type/$data->id_div_type/\" '/></a>" :'<img src="/img/admin/s.gif" width="24" height="1" >';
			$go_to_module = '';
			//"<a href ='#' title='Просмотр'><img src='/images/search.gif' onclick='javascript:window.location = \"/$url\" '/></a>" .
			return 	$go_to_module.
					"<a href ='#' title='$title' pub=\"true\" id_page=\"$data->id\" active=\"".$data->is_active."\" ><img src='/img/admin/active_" . $data->is_active . ".png' /></a>" . 
					"<a href ='#' title='Редактировать' ><img src='/img/admin/edit.png' onclick='javascript:window.location = \"/pages/$lang/admin_pages/edit/id/$data->id/\" '/></a>" .
					"<a href ='#' title='Добавить' ><img src='/img/admin/add.png' onclick='javascript:window.location = \"/pages/$lang/admin_pages/add/parent_id/$data->id/\" '/></a>" .$delete;
					;
		} else{
			return "<a href ='#' title='Добавить' ><img src='/img/admin/add.png' onclick='javascript:window.location = \"/pages/$lang/admin_pages/add/parent_id/$data->id/\" '/></a>" ;
		}		
	
	}
}