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
	 * Добавление новой языковой версии сайта
	 *
	 * @param string $version
	 */
	public function addVersion($version) {
		$where = array ($this->getAdapter ()->quoteInto ( 'version = ?', 'ru' ) );
		$cur_version = $this->fetchAll ( $where, 'level' );
		$user = Security::getInstance ()->getUser ();		
		
		foreach ( $cur_version as $key => $data ) {
			$conformParent = $this->getConformParent ( ( int ) $data->parentId, $version );
			$oldId = $data->id;
			$data->id = '';
			$data->version = $version;
			$data->createdBy = $user->id;
			$data->pubDate = date ( "Y-m-d H:i:s" );
			$data->parentId = empty ( $conformParent ) ? 0 : $conformParent->id;
			$this->insert ( $data->toArray () );
			$new = $this->getVersionPage ( $data );
			PagesOptions::getInstance ()->addVersion ( $oldId, $new->id );
			Menu::getInstance ()->addVersion ( $oldId, $new->id );
			$new = $new->toArray ();
			if ($new ['type'] != 'root') {
				$new ['lang'] = $version;
				
				$comments = Templates::getInstance ()->getComments ( $new ['template'] );
				$controller = (Templates::getInstance ()->filtered ( $comments->module ) == 'default') ? 'page' : 'index';
				Router::getInstance ()->addRoute ( $new, 'index', $controller, $comments->module );
			}
		}
	
	}
	
	/**
	 * Получение сраницы определенной языковой версии
	 *
	 * @param array $data
	 * @return object
	 */
	public function getVersionPage($data) {
		$where = array ($this->getAdapter ()->quoteInto ( 'path = ?', $data->path ), $this->getAdapter ()->quoteInto ( 'version = ?', $data->version ), $this->getAdapter ()->quoteInto ( 'type = ?', $data->type ) );
		$page = $this->fetchRow ( $where );
		
		return $page;
	}
	
	/**
	 * Получение страницы
	 *
	 * @param int $id
	 * @param string $order
	 * @return object
	 */
	public function getPage($id, $order = null) {
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', ( int ) $id );
		$page = $this->fetchRow ( $where, $order );		
		
		return $page;
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
		$where = array ($this->getAdapter ()->quoteInto ( 'level = ?', $page->level ), $this->getAdapter ()->quoteInto ( 'sortId = ?', $page->sortId ), $this->getAdapter ()->quoteInto ( 'version = ?', $version ) );
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
	 * @return array
	 */
	public function getPageByParam($name, $value, $version='ru') {
		$where =array(
			$this->getAdapter ()->quoteInto ( "$name = ?", $value ),
			$this->getAdapter ()->quoteInto ( "version = ?", $version )
		);		
		return $this->fetchRow ( $where );
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
		$where = array ($this->getAdapter ()->quoteInto ( "version = ?", $lang ), $this->getAdapter ()->quoteInto ( "module = ?", $module ) );
		
		return $this->fetchAll ( $where );
	}
	
	/**
	 * Построение дерева для ExtJS
	 *
	 * @param string $version
	 * @param int $parentId
	 * @param int $level
	 * @return array
	 */
	public function getTree($version, $parentId = 0, $level = 0) {
		$return = array ( );
		$nodes = array ( );
		$where = array ($this->getAdapter ()->quoteInto ( 'version = ?', $version ), $this->getAdapter ()->quoteInto ( 'level = ?', $level ), $this->getAdapter ()->quoteInto ( 'parentId = ?', $parentId ), $this->getAdapter ()->quoteInto ( 'deleted = ?', 0 ) );
		$nodes = $this->fetchAll ( $where, 'sortId' );
		$html = '';
		if ($nodes->count()){
			$html = '<ul>';
			foreach ( $nodes as $data ) {
				
				if ($this->getCountOfChildren ( $data->id ) > 0) {
					$html.="<li id=\"$data->id\" ><div class=\"all\"><ins>&nbsp;</ins><a href=\"#\" class=\"tree_item_name\">$data->name</a><div class=\"controls\">".$this->getDuration ( $data )."</div></div>";
					$html.=$this->getTree ( $version, $data->id, $data->level + 1 );
					$html.="</li>";
					//$return [] = array ('task' => $data->name, 'duration' => $this->getDuration ( $data ), 'user' => Security::getInstance ()->getUser ()->username, 'id' => $data->id, 'uiProvider' => 'col', 'cls' => 'master-task', 'iconCls' => 'task-folder', 'children' => $this->getTree ( $version, $data->id, $data->level + 1 ) );
				} else {
					//$return [] = array ('task' => $data->name, 'duration' => $this->getDuration ( $data ), 'user' => Security::getInstance ()->getUser ()->username, 'id' => $data->id, 'uiProvider' => 'col', 'leaf' => 'true', 'iconCls' => 'task' );
					$html.="<li id=\"$data->id\"><div class=\"all\" ><ins>&nbsp;</ins><a href=\"#\" class=\"tree_item_name\">$data->name</a><div class=\"controls\">".$this->getDuration ( $data )."</div></div>";
					
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
	 * @param int $parentId
	 * @param int $level
	 * @return array
	 */
	public function getSitemap($version, $parentId = 1, $level = 1) {
		$return = array ( );
		$nodes = array ( );
		$where = array (
			$this->getAdapter ()->quoteInto ( 'version = ?', $version ), 
			$this->getAdapter ()->quoteInto ( 'level = ?', $level ), 
			$this->getAdapter ()->quoteInto ( 'parentId = ?', $parentId ), 
			$this->getAdapter ()->quoteInto ( 'deleted = ?', 0 ),
			$this->getAdapter ()->quoteInto ( 'sitemap = ?', 1 )
		 );
		$nodes = $this->fetchAll ( $where, 'sortId' );
		$html = '';
		if ($nodes->count()){
			$html = '<ul>';
			foreach ( $nodes as $data ) {
				
				if ($this->getCountOfChildren ( $data->id ) > 0) {
					$html.="<li id=\"$data->id\" ><a href=\"/$data->path\" >$data->name</a>";
					$html.=$this->getSitemap ( $version, $data->id, $data->level + 1 );
					$html.="</li>";
					//$return [] = array ('task' => $data->name, 'duration' => $this->getDuration ( $data ), 'user' => Security::getInstance ()->getUser ()->username, 'id' => $data->id, 'uiProvider' => 'col', 'cls' => 'master-task', 'iconCls' => 'task-folder', 'children' => $this->getTree ( $version, $data->id, $data->level + 1 ) );
				} else {
					//$return [] = array ('task' => $data->name, 'duration' => $this->getDuration ( $data ), 'user' => Security::getInstance ()->getUser ()->username, 'id' => $data->id, 'uiProvider' => 'col', 'leaf' => 'true', 'iconCls' => 'task' );
					$html.="<li id=\"$data->id\" ><a href=\"/$data->path\" >$data->name</a>";
					
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

        $all = $this->fetchAll("parentId = $id");
        $parentRow = $this->fetchRow("id = $id");

        $this->update(array('level' => $parentRow->level+1), "parentId = $id");
        
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
	 * Проверка действительности события dnd
	 *
	 * @param int $id
	 * @param int $parentId
	 * @return boolean
	 */
	public function isReplace($id, $parentId) {
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$node = $this->fetchRow ( $where );
		
		return $node->parentId == $parentId ? false : true;
	}
	
	/**
	 * Получение корневого элемента
	 * определенной языковой версии
	 *
	 * @param string $lang
	 * @return object
	 */
	public function getRoot($lang = 'ru') {
		$where = array ($this->getAdapter ()->quoteInto ( 'type = ?', 'root' ), $this->getAdapter ()->quoteInto ( 'version = ?', $lang ) );
		
		return $this->fetchRow ( $where );
	}
	
	/**
	 * Получение страниц для вывода на карте сайта
	 *
	 * @param string $lang
	 * @param int $id
	 * @return array
	 *//*
	public function getSiteMap($lang, $id = 1) {
		$where = array (
			$this->getAdapter ()->quoteInto ( 'parentId = ?', $id ),
			$this->getAdapter ()->quoteInto ( 'version = ?', $lang ),
			$this->getAdapter ()->quoteInto ( 'sitemap = ?', 1 )
			 );
		$nodes = array ( );
		$nodes = $this->fetchAll ( $where, "sortId" );
		
		foreach ( $nodes as $data ) {
			$data->path = $lang == 'ru' ? $data->path : $lang . '/' . $data->path;
			$this->_tree [] = $data;
			$this->getSiteMap ( $lang, $data->id );
		}
		
		return $this->_tree;
	}*/
	
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
		$where = $this->getAdapter ()->quoteInto ( 'parentId = ?', $id );
		$nodes = $this->fetchAll ( $where );
		
		$array = array ( );
		
		foreach ( $nodes as $key => $data ) {
			$array [$data->name] = $data->id;
		}		
		return $array;
	}

        /**
	 * Получение дочерних элементов и их адресов для меню
	 *
	 * @param int $id
	 * @return array
	 */
	public function getChildrenAndURLs($id) {
		$where[] = $this->getAdapter ()->quoteInto ( 'parentId = ?', $id );
                $where[] = $this->getAdapter ()->quoteInto ( 'published = ?', 1 );
		$nodes = $this->fetchAll ( $where, 'sortId ASC' );

		$array = array ( );

		foreach ( $nodes as $key => $data ) {
			$array [$data->name] = $data->path;
		}
		return $array;
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
		
		foreach ( $ids as $key => $id ) {
			if ($this->hasChild ( $id )) {
			}
		}
		
		return true;
	}
	
	public function unpubPage($id) {
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$this->update ( array ('published' => '0', 'unpubDate' => date ( "Y-m-d H:i:s" ) ), $where );
	}
	
	public function pubPage($id) {
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$this->update ( array ('published' => '1', 'pubDate' => date ( "Y-m-d H:i:s" ) ), $where );
	}
	
	public function deletePageOptions($ids) {
		$where = $this->getAdapter ()->quoteInto ( "pageId IN (?)", $ids );
		//Loader::loadPublicModel ( 'PagesOptions' );
		PagesOptions::getInstance ()->deleteOptions ( $where );
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
	
	/**
	 * Получение пути для страницы с переданным разделителем
	 *
	 * @param int $parentId
	 * @param string $separator
	 * @return string
	 */
	public function generateStringPath($parentId, $separator) {
		$reverse = $this->generateReversePath ( $parentId );
		$result = '';
		
		for($i = count ( $reverse ) - 1; $i >= 0; $i --) {
			$result .= $reverse [$i];
			$result .= $separator;
		}
		
		return $result;
	}
	
	/**
	 * Создание копии страницы
	 *
	 * @param int $id
	 */
	public function copyPage($id) {
		$page = $this->getPage ( $id );
		$this->addPage ( $page );
	}
	
	/**
	 * Создание копии страницы вместе с поддеревом
	 *
	 * @param int $id
	 */
	public function copyPageWithChildren($id) {
		$page = $this->getPage ( $id );
		$this->addPage ( $page );
		
		foreach ( $this->getChildren ( $page->id ) as $key => $data ) {
			$this->copyPageWithChildren ( $data );
		}
	}
	
	public function search($search){
		 $dbAdapter = Zend_Registry::get('db');
		 $sql = $dbAdapter->quoteInto("SELECT name, id, 'pages' as TYPE, path FROM site_content WHERE (site_content.name LIKE '%".$search."%' OR
		 	 site_content.introText LIKE '%".$search."%'
		 	OR site_content.content LIKE '%".$search."%' ) AND (site_content.deleted=0 AND site_content.published =1)  ; ",null);
				
		 $result = $dbAdapter->query($sql);
		 return  $result->fetchAll();
		
	}
	
	public function remove($id){
		$page= $this->find($id)->current();
		if ($this->getCountOfChildren($id)){
			$childs = $this->fetchAll('parentId='.(int)$id);
			foreach ($childs as $child){
				$this->remove($child->id);
			}
		}
		if (!is_null($page)){
			@unlink(DIR_PUBLIC.'pics/default/'.$page->img);			
			$page->delete();	
		}	
	}
	
	
	public function reindex(){
		$index = New Ext_Search_Lucene(Ext_Search_Lucene::PAGES, true);
		//$pages_rowset = $this->fetchAll('published=1')
		$count = 10 ;
		$offset = 0 ;
		while( ( $rowset = $this->fetchAll( 'published=1', null, $count, $offset ) ) && ( 0 < $rowset->count() ) ) {
			while( $rowset->valid() ) {
				$row = $rowset->current() ;
				//
				// Prepare document
				//
				$doc = new Ext_Search_Lucene_Document();
				$doc->setUrl($row->path);
				$doc->setTitle($row->name);
				$doc->setId($row->id);
                                $doc->setContent(strip_tags($row->content));
				
		        $index->addDocument( $doc ) ;

				$rowset->next() ;
			}
			$offset += $count ;
		}

		$index->commit() ;
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
		$where = "path='$path' AND version='$lang' AND type!='root'";
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
	 * Получение контента определенного типа (page, image, root...)
	 *
	 * @param string $type
	 * @param string $order
	 * @return array
	 */
	private function getContent($type, $order = null) {
		$where = $this->getAdapter ()->quoteInto ( "type = ? OR type = 'root'", $type );
		
		return $this->fetchAll ( $where, $order );
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
		$page = $this->getPage ( $id );
		$parent = $this->getPage ( $parentId );
		$this->getAdapter ()->query ( "UPDATE $this->_name SET parentId = :parentId, level = :level, sortId = sortId + 1 WHERE parentId = :id AND sortId >= :sort", array ('parentId' => $parent->parentId, 'level' => $parent->level, 'id' => $parent->parentId, 'sort' => $parent->sortId ) );
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$this->update ( 
			array (
				'parentId' => $parent->parentId, 
				'level' => $parent->level, 
				'sortId' => $parent->sortId//, 
				//'path' => $this->generateStringPath ( $parent->parentId, '/' ) . $page->path 
			),
			
			 $where );
	}
	
	/**
	 * Перемещение страницы через dnd,
	 * при котором страница выше является родительской
	 *
	 * @param int $id
	 * @param int $parentId
	 * @param string $point
	 */
	private function replaceBelow($id, $parentId, $point) {
		$page = $this->getPage ( $id );
		$parent = $this->getPage ( $parentId );
		
		$this->getAdapter ()->query ( "UPDATE $this->_name SET parentId = :parentId, level = :level, sortId = sortId + 1 WHERE parentId = :id AND sortId > :sort", array ('parentId' => $parent->parentId, 'level' => $parent->level, 'id' => $parent->parentId, 'sort' => $parent->sortId ) );
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$this->update ( array (
						'parentId' => $parent->parentId, 
						'level' => $parent->level, 
						'sortId' => $parent->sortId + 1//, 
						//'path' => $this->generateStringPath ( $parent->parentId, '/' ) . $page->path
		 ), $where );
	}
	
	/**
	 * Перемещение страницы через dnd, при котором
	 * она была брошена на страницу, тем самым была добавлена
	 * в конец списка вложенных
	 *
	 * @param int $id
	 * @param int $parentId
	 * @param string $point
	 */
	private function replaceAppend($id, $parentId, $point) {
		$page = $this->getPage ( $id );
		$parent = $this->getPage ( $parentId );
		$max_sort = $this->getMaxSort ( $parentId );
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $id );
		$this->update ( array (
			'parentId' => $parentId, 'level' => $parent->level + 1, //на 1 больше родительского уровня
			'sortId' => $max_sort + 1, //на 1 больше максимального- добавляется в конец
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
	 * Получение минимального sortId 
	 * родительской страницы
	 *
	 * @param int $id
	 * @return int
	 */
	private function getMinSort($id) {
		return $this->getSort ( $id, 'ASC' );
	}
	
	/**
	 * Получение максимального sortId 
	 * родительской страницы
	 *
	 * @param int $id
	 * @return int
	 */
	private function getMaxSort($id) {
		return $this->getSort ( $id, 'DESC' );
	}
	
	/**
	 * Получение sortId 
	 * родительской страницы,
	 * определенным образом сортируя(asc, desc)
	 *
	 * @param int $id
	 * @return int
	 */
	private function getSort($id, $type) {
		$order = "sortId $type";
		$where = $this->getAdapter ()->quoteInto ( 'parentId = ?', $id );
		$page = $this->fetchRow ( $where, $order );
		
		return $page ? $page->sortId : 0;
	}
	
	/**
	 * Проверка на родительство
	 *
	 * @param int $id
	 * @return boolean
	 */
	private function hasChild($id) {
		try {
			$where = $this->getAdapter ()->quoteInto ( 'parentId = ?', $id );
			$root = $this->fetchRow ( $where );
		} catch ( Exception $e ) {
			echo $e->getMessage ();
			exit ();
		}
		
		return $root ? true : false;
	}
	
	/**
	 * Получение "перевернутого" пути страницы, 
	 * так как первоначально путь формируется от детей - к отцу
	 *
	 * @param int $parentId
	 * @return string
	 */
	private function generateReversePath($parentId) {
		$where = $this->getAdapter ()->quoteInto ( 'id = ?', $parentId );
		$parent = $this->fetchRow ( $where );
		
		if ($parent && $parent->type != 'root') {
			$this->_path [] = $parent->path;
			$this->generateReversePath ( $parent->parentId );
		}
		
		return $this->_path;
	}
	
	/**
	 * Получение пути страницы в виде массива
	 *
	 * @param int $parentId
	 * @return array
	 */
	private function generateArrayPath($parentId) {
		$reverse = $this->generateReversePath ( $parentId );
		$result = array ( );
		
		for($i = count ( $reverse ) - 1; $i >= 0; $i --) {
			$result [] = $reverse [$i];
		}
		
		return $result;
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
		$maxId = $this->getMaxId ();
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
			'version' => isset ( $data ['lang'] ) ? $data ['lang'] : $parent->version,
			'published' => isset ( $data ['published'] ) ? '1' : '0',
			'pubDate' => date ( "Y-m-d H:i:s" ),
			'sitemap' => isset ( $data ['sitemap'] ) ? '1' : '0',
			'show_childs' => isset ( $data ['show_childs'] ) ? '1' : '0',
			'unpubDate' => date ( "Y-m-d H:i:s" ),
			'introText' => isset($data['introText']) ? $data['introText'] : '',
			'content' => isset ( $data ['content'] ) ? $data ['content'] : '',
			'template' => isset ( $data ['template'] ) ? $data ['template'] : 'default',
			'module' => $module, 'createdBy' => $user->id,
			'editedBy' => $user->id,
			'deletable'=>1,
			'deleted' => '0',
			'deletedBy' => $user->id,
			'publishedBy' => $user->id,
			'parentId' => isset ( $data ['parent_id'] ) ? $data ['parent_id'] : '1',
			'level' => $parent->level + 1,
			'sortId' => $countOfChildren + 1,
			'name' => isset ( $data ['name'] ) ? $data ['name'] : '',
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
			'published' => isset ( $data ['published'] ) ? (int)$data ['published'] : '0',
			'sitemap' => isset ( $data ['sitemap'] ) ? (int)$data ['sitemap'] : '0',
			'id_div_type' => isset ( $data ['id_div_type'] ) ? $data ['id_div_type'] : '0',
			'show_childs' => isset ( $data ['show_childs'] ) ? (int)$data ['show_childs'] : '0',
			'introText' => isset($data['introText']) ? $data['introText'] : '',
			'content' => isset ( $data ['content'] ) ? $data ['content'] : '',
			'template' => isset ( $data ['template'] ) ? $data ['template'] : 'default',
			'editedBy' => $user->id,
			'name' => isset ( $data ['name'] ) ? $data ['name'] : '',
			'inside_items' => isset ( $data ['inside_items'] ) ? $data ['inside_items'] : '0',
			'path' => $data ['path'] );
		
		return $result;
	}
	
	/**
	 * Получение данных копируемой страницы
	 *
	 * @param object $data
	 * @return array
	 */
	private function getCopyDataPage($data) {
		$user = Security::getInstance ()->getUser ();
		$countOfChildren = $this->getCountOfChildren ( $data->parentId );
		$maxId = $this->getMaxId ();
		$data = $data->toArray ();
		$number = $this->getCountOfCopies ( $data ['name'] ) + 1;
		//$data ['id'] = $maxId + 1;
		unset($data['id']);
		$data ['name'] = $data ['name'] . "_$number";
		$data ['version'] = $data ['version'];
		$data ['path'] = $data ['path'] . "_$number";
		$data ['published'] = '0';
		$data ['pubDate'] = date ( "Y-m-d H:i:s" );
		$data ['createdBy'] = $user->id;
		$data ['sortId'] = $countOfChildren + 1;
		
		return $data;
	}
	
	/**
	 * Получение количества копий страницы
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
	 * Получение максимального ID
	 *
	 * @return unknown
	 */
	private function getMaxId() {
		$max = $this->fetchRow ( null, 'id DESC' );
		
		return $max->id;
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
		if ($data->published == '0') {			
			$title = 'Включить';
		}
		$lang = $data->version;
		if ($data->type!='root'){
			$module = ($data->module == 'default' || empty ( $data->module )) ? 'pages' : $data->module;
			
			$url = ($lang == 'ru') ? $data->path : $lang . '/' . $data->path;
			$delete = $data->deletable==1 ? "<a href ='#' title='Удалить' delete=\"true\" id_page=\"$data->id\" ><img src='/img/admin/delete.gif' /></a>" : '';
			//$copy = $data->deletable==1 ? "<a href ='#' title='Сделать копию' ><img src='/images/plus_b.gif' onclick='javascript:window.location = \"/pages/$lang/admin_pages/copy/id/$data->id/\" '/></a>" :'';
			
			
			$go_to_module = $data->inside_items ==1 ? "<a href ='#' title='Перейти внутрь раздела' ><img src='/img/admin/folder.gif' onclick='javascript:window.location = \"/pages/$lang/admin_pages/gotomodule/id_page/$data->id/id_type/$data->id_div_type/\" '/></a>" :'<img src="/img/admin/s.gif" width="24" height="1" >';
			//"<a href ='#' title='Просмотр'><img src='/images/search.gif' onclick='javascript:window.location = \"/$url\" '/></a>" .
			return 	$go_to_module.
					"<a href ='#' title='$title' pub=\"true\" id_page=\"$data->id\" ><img src='/img/admin/active_" . $data->published . ".gif' /></a>" . 
					"<a href ='#' title='Редактировать' ><img src='/img/admin/redact.gif' onclick='javascript:window.location = \"/pages/$lang/admin_pages/edit/id/$data->id/\" '/></a>" .
					"<a href ='#' title='Добавить' ><img src='/img/admin/plus_krug.gif' onclick='javascript:window.location = \"/pages/$lang/admin_pages/add/parent_id/$data->id/\" '/></a>" .$delete;
					;
		} else{
			return "<a href ='#' title='Добавить' ><img src='/img/admin/plus_krug.gif' onclick='javascript:window.location = \"/pages/$lang/admin_pages/add/parent_id/$data->id/\" '/></a>" ;
		}		
	
	}
}