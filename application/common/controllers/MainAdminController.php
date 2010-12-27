<?

//require_once('Zend/Controller/Action.php');

abstract class MainAdminController extends Zend_Controller_Action {
    
    public function __call($method, $args) {

    }
    
    
    
   /**
     * Все страницы  админки кроме формы авторизации проходят этот метод
     *
     */
    public function preDispatch() {
        Security::getInstance()->init();
        $this->initView();
        $this->view->strictVars(false);
        $this->view->addScriptPath(DIR_LAYOUTS) ;
        $this->view->addHelperPath(Zend_Registry::get('helpersPaths') , 'View_Helper') ;
        $this->view->addHelperPath(array(DIR_LIBRARY. 'Ext'. DS . 'View' . DS . 'Helper' . DS), 'Ext_View_Helper');
        
        $this->view->layout()->lang = $this->_getParam('lang', 'ru');
        $this->view->layout()->controller = $this->getRequest()->getControllerName();
        $this->setTemplate();
        $except = $this->getRequest()->getControllerName() . $this->getRequest()->getActionName();
        $security = Security::getInstance();        
        $user = $security->getUser();              
   		if ($user!=null && isset($user->username)){
        	$this->view->placeholder('user_login')->set($user->username);
        }
        if ($security->checkManagerAllow() && $this->getRequest()->getModuleName()!='orders' && $except != 'userslogout') {
		$_SESSION['prev_admin_url'] = $_SERVER['REQUEST_URI'];
            $this->_redirect('/orders/ru/admin_orders/');
        }
        else if(!$security->checkManagerAllow() && !$security->checkAdminAllow() && $except != 'indexlogin') {
		$_SESSION['prev_admin_url'] = $_SERVER['REQUEST_URI'];
            $this->_redirect('/admin/login');
        }
    	
    }

    /**
     * Обрезание параметра до указанной длины при получении
     * По умолчанию - до 50
     *
     * @param string $param
     * @param int $max_lenth
     * @return string
     */
    public function getParam($param, $max_lenth = 0) {
        if($max_lenth == 0)
            $max_lenth = 50;

        return substr($this->_request->getParam($param), 0, $max_lenth);
    }

    /**
     * Сюда попадают при 404 ошибке
     *
     */
    public function error404Action() {

    }

    public function dateToDb($date) {
        if ($date) {
            preg_match('/([\d]{2})[\.\/-]{1}([\d]{2})[\.\/-]{1}([\d]{4})/i', $date, $matches);
            return $matches[3].'-'.$matches[2].'-'.$matches[1];
        }
        return '';
    }

    public function dateFromDb($date) {
        preg_match('/([\d]{4})-([\d]{2})-([\d]{2})/', $date, $matches);
        //print_r($matches);
        return $matches[3].'.'.$matches[2].'.'.$matches[1];
    }

    /**
     * Выход из системы администрирования
     *
     */
    public function logoutAction() {
        Security::getInstance()->logout();
        $this->_redirect('/admin/');
    }

//	/**
//	 * Переход в случае ошибки и, наоборот - сэксесса
//	 *
//	 * @return 
//	 */
//	protected function away($url = '/admin/'){
//		return $this->_redirect($url);
//	}

    /**
     * Инициализация FckEditor-а
     *
     * @param string $name
     * @param string $width
     * @param string $height
     * @return object
     */
    protected function getFck($name = 'fckeditor', $width = '70%', $height = '400', $type='Default') {
        require_once(ROOT_DIR. "fckeditor" . DS . "fckeditor.php");
        $oFCKeditor = new FCKeditor($name) ;
        $oFCKeditor->BasePath = '/fckeditor/';
        $oFCKeditor->Width  = $width ;
        $oFCKeditor->Height = $height ;
        $oFCKeditor->Config['SkinPath'] = $oFCKeditor->BasePath . 'editor/skins/silver/';
        $oFCKeditor->ToolbarSet =$type;

        return $oFCKeditor;
    }

    /**
     * Добавление роута на страницу
     *
     * @param array $data
     * @param string $action
     * @param string $controller
     * @param string $module
     * @return string
     */
    /*protected function addRoute($data, $action = 'index', $controller = 'page', $module = 'default'){
		Loader::loadCommon('Router');
		
		if(!Router::getInstance()->addRoute($data, $action, $controller, $module)){
			$error = "Такой URL уже существует!";
		}
		
		return '';
	}*/

    /**
     * Установка шаблона
     *
     */
    protected function setTemplate() {
        $this->_helper->layout->setLayout( "admin/default" ) ;
    }


	public function getMeta($type, $id){
		$options = PagesOptions::getInstance()->fetchRow("type='$type' AND item_id=".(int)$id);
		if ($options==null){
			$options = PagesOptions::getInstance()->fetchNew();
		}
		return $options;
		
	}
	
	public function editMeta($type, $id){
		$meta = $this->_getParam('meta', null);
		$meta = $this->trimFilter($meta);	
		$meta = $this->htmlFilter($meta);
		if ($type!='' && $id && $meta){
					
			$options = PagesOptions::getInstance()->fetchRow("type='$type' AND item_id=".(int)$id);
			if ($options!=null){
				$options->setFromArray($meta);
				$options->save();
			}else {
				$meta['type'] = $type;
				$meta['item_id'] = $id;
				$options = PagesOptions::getInstance()->createRow($meta)->save();				
			}
			
		}
	}

    protected function getFilterInput(array $validators, array $options = null) {
        $filter = new Zend_Filter_Input(array(),$validators, $_POST);
        $filter->setDefaultEscapeFilter(new Zend_Filter_HtmlEntities(array('charset'=> 'utf-8')));
        $options = Configurator::getConfig('messages')->toArray();

        if ($options) {
            $filter->setOptions($options['input']);
            $email_validator = new  Zend_Validate_EmailAddress();
            $email_validator->setMessages($options['email']);
        }

        return $filter;
    }

    /**
     * @param array $data
     * @return array
     **/
    public function trimFilter($data) {
        // Предоставляет возможность создания цепочек фильтров
        // Создание цепочки фильтров и добавление в нее фильтров
        $filter = new Zend_Filter();
        $filter->addFilter(new Zend_Filter_StringTrim());
        //$filter->addFilter(new Zend_Filter_HtmlEntities(ENT_COMPAT, 'UTF-8'))	;

        if ($data) {
            foreach ($data as $key => $value) {
                $data[$key] = $filter->filter($value);
            }
        }

        return $data;

    }

    /**
     * @param array $data
     * @return array
     **/
    public function htmlFilter($data) {
        // Предоставляет возможность создания цепочек фильтров
        // Создание цепочки фильтров и добавление в нее фильтров
        $filter = new Zend_Filter();

        $filter->addFilter(new Zend_Filter_HtmlEntities(array('charset'=> 'utf-8')))	;

        if ($data) {
            foreach ($data as $key=> $value) {
                $data[$key]=$filter->filter($value);
            }
        }

        return $data;

    }


    /**
     * Upload file
     *
     * @param string $sTempFileName
     * @param string $path
     * @param string $fileName
     * @return bollean
     */
    public function upload_file($sTempFileName, $path, $fileName='') {
        @chmod($sTempFileName, 0777);
        return move_uploaded_file($sTempFileName, $path.$fileName);
    }

    public  function img_resize($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100) {
        if (!file_exists($src)) return false;
        $size = getimagesize($src);        
        if ($size === false) return false; 
        
        // Определяем исходный формат по MIME-информации, предоставленной
        // функцией getimagesize, и выбираем соответствующую формату
        // imagecreatefrom-функцию.
        $format = strtolower(substr($size['mime'], strpos($size['mime'],'/')+1));
        $icfunc = "imagecreatefrom" . $format;
        $scfunc = "image" . $format;
        if (!function_exists($icfunc)) return false;
       

        $x_ratio = $width / $size[0];
        $y_ratio = $height / $size[1];




        $ratio = min($x_ratio, $y_ratio);
         if ($ratio>=1){
        	if (copy($src, $dest)){
        		return true;
        	} else {
        		return false;
        	}
        	
        }
        if ($format == 'png') return false;
        $use_x_ratio = ($x_ratio == $ratio);
        $use_x_ratio = true;

        $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
        $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
        //$new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
        $new_left    = $use_x_ratio  ? 0 : 0;
        // $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);
        $new_top     = !$use_x_ratio ? 0 : 0;

        $isrc = $icfunc($src);
        //$idest = imagecreatetruecolor($width, $height);
        $idest = imagecreatetruecolor($new_width, $new_height);

        imagefill($idest, 0, 0, $rgb);
        imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0,
                $new_width, $new_height, $size[0], $size[1]);

        $scfunc($idest, $dest, $quality);

        imagedestroy($isrc);
        imagedestroy($idest);

        return true;

    }

    public  function rus2translit($string) {
        $converter = array(
                'а' => 'a',   'б' => 'b',   'в' => 'v',
                'г' => 'g',   'д' => 'd',   'е' => 'e',
                'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
                'и' => 'i',   'й' => 'y',   'к' => 'k',
                'л' => 'l',   'м' => 'm',   'н' => 'n',
                'о' => 'o',   'п' => 'p',   'р' => 'r',
                'с' => 's',   'т' => 't',   'у' => 'u',
                'ф' => 'f',   'х' => 'h',   'ц' => 'c',
                'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
                'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
                'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

                'А' => 'A',   'Б' => 'B',   'В' => 'V',
                'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
                'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
                'И' => 'I',   'Й' => 'Y',   'К' => 'K',
                'Л' => 'L',   'М' => 'M',   'Н' => 'N',
                'О' => 'O',   'П' => 'P',   'Р' => 'R',
                'С' => 'S',   'Т' => 'T',   'У' => 'U',
                'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
                'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
                'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
                'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }   
}
