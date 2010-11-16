<?
define('ROLE_GUEST', 'guest');
define('ROLE_MEMBER', 'member');
define('ROLE_ADMIN', 'admin');
define('ROLE_MANAGER', 'manager');

define('RESOURCE_INDEX', 'index');
define('RESOURCE_COMMON', 'common');
define('RESOURCE_ARTICLE', 'articles');
define('RESOURCE_SELL', 'sell');
define('RESOURCE_REGISTERED', 'registered');
define('RESOURCE_ADMINISTRATOR', 'administrator');
define('RESOURCE_ORDERS', 'orders');


define('ACTION_VIEW', 'view');
define('ACTION_POST', 'post');

class Security {
    /**
     * @var Security
     */
    protected static $_instance = null;

    /**
     * @var Zend_Acl
     */
    private $_acl = null;

    /**
     * @var Zend_Auth
     */
    private $_auth = null;

    /**
     * @var Zend_Auth_Adapter
     */
    private $_authAdapter = null;

    /**
     * @var object
     */
    private $_user = null;

    /**
     * Singleton instance
     * @return Security
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
		Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('backend'));
        return self::$_instance;
    }

    /**
     * initializing of the constructor
     *
     */
    public function init() {
        $this->initAcl();
        $this->initAuth();
        $this->initAuthAdapter();
        $this->authenticate();
    }

    /**
     * Получение текущего пользователя
     *
     * @return object
     */
    public function getUser() {    	
       /* if(!$this->_user || !isset($this->_user->role))     
            $this->_user = (object)array('username' => ROLE_GUEST, 'role' => ROLE_GUEST);
		*/
    	return $this->_auth->getStorage()->read();
       // return $this->_user;
    }

    /**
     * Редактирование данных админа
     *
     * @param string $username
     * @param string $password
     */
    public function editAdmin($username = null, $password = null) {
        Loader::loadPublicModel('UsersTable');
        ;
        $users = new UsersTable();

        if(!$username) {
            $username = $users->fetchAll("role = 'admin'")->current()->username;
        }

        if(!$password) {
            $password = $users->fetchAll("role = 'admin'")->current()->password;
        }

        $users->update(array('username'=>$username, 'password'=>$password), "role = 'admin'");
    }

    /**
     * Поиск пользователя по имени либо ID
     *
     * @param string $username
     * @param int $userid
     * @return object
     */
    public function findUser($username, $userid = null) {
        Loader::loadPublicModel('UsersTable');
        ;
        $users = new UsersTable();

        if($userid) {
            $rowset = $users->fetchAll("id = '$userid'");
        }
        else {
            $rowset = $users->fetchAll("username = '$username'");
        }

        if(count($rowset)) {
            return $rowset->current();
        }

        return null;


    }

    /**
     *  Проверка идентификации member-а
     *
     * @return boolean
     */
    public function checkMemberAllow() {
        if($this->getUser()->role != ROLE_MEMBER) {
            return false;
        }
        return true;
    }

    /**
     *
     * @return Zend_Acl
     */
    private function getAcl() {
        return $_acl;
    }

    /**
     * @return Zend_Auth
     */
    private function getAuth() {
        return $this->_auth;
    }

    /**
     *
     * @return Zend_Auth_Adapter
     */
    private function getAuthAdapter() {
        return $_authAdapter;
    }

    /**
     * Проверка идентификации
     *
     * @return object
     */
    public function checkRoleAllow($role) {
    	if ($this->getUser()!=null){
        	return ($this->getUser()->role == $role);
    	}
    	return false;
    }

    /**
     * Проверка идентификации админа
     *
     * @return boolean
     */
    public function checkAdminAllow() {
        if($this->checkRoleAllow(ROLE_ADMIN)) {
            return true;
        }

        return false;
    }

    /**
     * Проверка идентификации менеджера
     *
     * @return boolean
     */
    public function checkManagerAllow() {        
        if($this->checkRoleAllow(ROLE_MANAGER)) {
            return true;
        }
        return false;
    }

    /**
     * Проветка идентификации member-а
     * либо админа
     *
     * @return unknown
     */
    public function checkAllow() {
        if($this->checkRoleAllow(ROLE_ADMIN) || $this->checkRoleAllow(ROLE_MEMBER)) {
            return true;
        }
        return false;
    }

    /**
     * Обновление данных юзера
     *
     * @param array $data
     * @param string $where
     * @param int $admin
     * @return string
     */
    public function updateUser($data, $where, $admin = null) {
        $users = Loader::loadPublicModel('UsersTable');
        $users = new UsersTable();
        $data = (array)$data;
        $username = $data['username'];
        $rowset = $users->fetchAll("username = '$username'");

        if(count($rowset))
            return 'Sorry! User with this Email already exist!';
        else
            $users->update($data, $where);


        if(!$admin) {
            $this->_auth->getStorage()->write((object)$data);
            $this->_user = $this->_auth->getStorage()->read(null, 'password');
        }
        return '';

    }

    public function checkMemberLogin($username, $password) {
        $loginMessage = null;

        if (empty($username)) {
            return $loginMessage = 'Incorrect Login or Password';
        }

        $this->_authAdapter -> setIdentity($username);
        $this->_authAdapter -> setCredential($password);

        // do the authentication
        $result = $this->_auth->authenticate($this->_authAdapter);

        if ($result->isValid()) {
            // success: store database row to auth's storage
            // system. (Not the password though!)
            $data = $this -> _authAdapter-> getResultRowObject(null, 'password');
            $this -> _auth ->getStorage() -> write($data);

            return $loginMessage;
        }

        return $loginMessage = 'Incorrect Login or Password';
    }

    /**
     * Проверка идентификации 
     *
     * @param string $username
     * @param string $password
     * @return string
     */
    public function checkAdminLogin($username, $password) {
        $loginMessage = null;

        if (empty($username)) {
            return $loginMessage = 'Incorrect Login or Password';
        }
		$this->_auth->clearIdentity();
        $this->_authAdapter -> setIdentity($username);
        $this->_authAdapter -> setCredential($password);
        // do the authentication
        $result = $this->_auth->authenticate($this->_authAdapter);

        if ($result->isValid()) {
            $data = $this -> _authAdapter->getResultRowObject(null, 'password');
            $this ->_auth->getStorage()->write($data);
            $this->_user = $this->_auth->getStorage()->read();
            return $loginMessage;
        }

        return $loginMessage = 'Incorrect Login or Password';
    }

    /**
     * Добавление нового юзера
     *
     * @param array $data
     * @return string
     */
    public function addUser($data) {
        Loader::loadPublicModel('UsersTable');
        $users = new UsersTable();
        $data = (array)$data;
        $username = $data['username'];
        $rowset = $users->fetchAll("username = '$username'");

        if(!count($rowset))
            $users->insert($data);
        else
            return 'Sorry! User with this Email already exist!';

        $data = (object)$data;

        $this->_authAdapter->setIdentity($data->username);
        $this->_authAdapter->setCredential($data->password);
        $result = $this->_auth->authenticate($this->_authAdapter);
        $data = $this->_authAdapter->getResultRowObject(null, 'password');
        $this->_auth->getStorage()->write($data);
        $this->_user = $this->_auth->getStorage()->read(null, 'password');

        return null;
    }

    /**
     * Получение ID юзера
     *
     * @param string $username
     * @return int
     */
    public function getUserId($username) {
        Loader::loadPublicModel('Users');
        $users = new UsersTable();
        $result = $users->fetchAll("username = '$username'");

        $user = $result->current();

        if(isset($user->id))
            return $user->id;

        return null;
    }

    /**
     * Получение ID юзера
     *
     * @param string $username
     * @return int
     */
    public function getUserRole($username) {
        $result = Users::getInstance()->fetchAll("username = '$username'");
        $user = $result->current();
        if(isset($user->role))
            return $user->role;

        return null;
    }

    /**
     * Выход из зарегеной зоны
     *
     */
    public function logout() {
        $this->_auth->clearIdentity();
    }

    private function authenticate() {
        if(!$this->_auth->hasIdentity()) {
            /*$this->_user = (object)array('username' => ROLE_GUEST, 'role' => ROLE_GUEST);
            $this->_auth->getStorage()->write($this->_user);*/
        	 $this->_user = null;
        }
        else {
            $this->_user = $this->_auth->getStorage()->read(null, 'password');
        }
    }

    private function initAcl() {
        if ($this->_acl === null) {
            $this->_acl = new Zend_Acl();
            // Roles
            $this->_acl->addRole(new Zend_Acl_Role(ROLE_GUEST))
                    ->addRole(new Zend_Acl_Role (ROLE_MEMBER))
                    ->addRole(new Zend_Acl_Role(ROLE_ADMIN))
                    ->addRole(new Zend_Acl_Role(ROLE_MANAGER))

                    ->add(new Zend_Acl_Resource(RESOURCE_INDEX))
                    ->add(new Zend_Acl_Resource(RESOURCE_COMMON))
                    ->add(new Zend_Acl_Resource(RESOURCE_SELL))
                    ->add(new Zend_Acl_Resource(RESOURCE_REGISTERED))
                    ->add(new Zend_Acl_Resource(RESOURCE_ADMINISTRATOR))
                    ->add(new Zend_Acl_Resource(RESOURCE_ARTICLE))
                    ->add(new Zend_Acl_Resource(RESOURCE_ORDERS))

                    // Guest may only view content
                    ->allow(ROLE_GUEST, array(RESOURCE_INDEX,RESOURCE_COMMON), ACTION_VIEW)
                    ->allow(ROLE_MEMBER, array(RESOURCE_INDEX,RESOURCE_COMMON,RESOURCE_REGISTERED,RESOURCE_SELL), ACTION_POST)
                    ->allow(ROLE_MANAGER, array(RESOURCE_ORDERS), null)
                    ->allow(ROLE_ADMIN, null, null); // unrestricted access
                
        }
        Zend_Registry::set('acl', $this->_acl);
    }

    private function initAuthAdapter() {
        $this->_authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('db'));
        $this->_authAdapter->setTableName('users');
        $this->_authAdapter->setIdentityColumn('username');
        $this->_authAdapter->setCredentialColumn('password');
        $select = $this->_authAdapter->getDbSelect();
        $select->where("activity = '1'");
//       		Zend_Registry::set('authAdapter', $this->_authAdapter);
    }

    private function initAuth() {
        $this->_auth = Zend_Auth::getInstance();
        $this->_auth->setStorage(new Zend_Auth_Storage_Session('backend'));
        //$this->_user = (object)array('username' => ROLE_GUEST, 'role' => ROLE_GUEST);
//			Zend_Registry::set('auth', $this->_auth);
    }
    

}
