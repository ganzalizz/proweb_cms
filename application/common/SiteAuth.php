<?
define('ROLE_USER', 'user');
define('ROLE_WORK', 'work');
define('ROLE_ADVT', 'advt');
define('ROLE_POWER_USER', 'power_user');

define('RESOURCE_WORK', 'work');
define('RESOURCE_ADVT', 'advt');


class SiteAuth {
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
     * 
     * @var string
     */
    private $_Identity = 'login';
    /**
     * 
     * @var string
     */
    private $_Credential = 'password';
    /**
     * 
     * @var string
     */
    private $_users_table = 'site_users';

    /**
     * Singleton instance
     * @return SiteAuth
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
		Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('frontend'));
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
        /*if(!$this->_user || !isset($this->_user->role)){
        	return null;
        }   
        return $this->_user;*/    	
    	return  $this->_auth->getStorage()->read();
    }

    public function getCurrentUserId() {
    	$user = $this->_auth->getStorage()->read();
        return $user->id;
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
    public function checkRoleAllow($resource=null, $privilege=null ) {
        //return ($this->getUser()->role == $role);
        if ($this->_acl->hasRole($this->getUser()->role)){
        	return $this->_acl->isAllowed($this->getUser()->role, $resource, $privilege);
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
    public function checkLogin($username, $password) {
        $loginMessage = null;
        if (empty($username)) {
            return $loginMessage = 'Incorrect Login or Password';
        }

        $this->_authAdapter -> setIdentity($username);
        $this->_authAdapter -> setCredential($password);
        $this->_auth->clearIdentity();
        $this->_auth->getStorage()->clear();
        // do the authentication
        $result = $this->_auth->authenticate($this->_authAdapter);

        if ($result->isValid()) {
            $data = $this -> _authAdapter-> getResultRowObject(null, 'password');
            $this -> _auth ->getStorage() -> write($data);
            $this->_user = $this->_auth->getStorage()->read(null, 'password');

            return $loginMessage;
        }

        return $loginMessage = 'Incorrect Login or Password';
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
        $result = $users->fetchAll("$this->_Identity = '$$this->_Identity'");

        $user = $result->current();

        if(isset($user->id))
            return $user->id;

        return null;
    }
    /**
     * проверка авторизован пользователь или нет
     */
    public function getIdentity(){
    	if ($this->_auth->getIdentity() && $this->checkRoleAllow()){
    		return  $this->_auth->getIdentity();
    	}
    	return false;
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
            //$this->_user = (object)array($this->_Identity => ROLE_GUEST, 'role' => ROLE_GUEST);
            //$this->_auth->getStorage()->write($this->_user);
        }
        else {
            $this->_user = $this->_auth->getStorage()->read(null, 'password');
        }
    }

    private function initAcl() {
        if ($this->_acl === null) {
            $this->_acl = new Zend_Acl();
            // Roles
            $this->_acl->addRole(new Zend_Acl_Role(ROLE_USER))
                        ->addRole(new Zend_Acl_Role(ROLE_WORK), ROLE_USER)
                        ->addRole(new Zend_Acl_Role(ROLE_ADVT), ROLE_USER)
                        ->addRole(new Zend_Acl_Role(ROLE_POWER_USER))

                        ->add(new Zend_Acl_Resource(RESOURCE_WORK))
                        ->add(new Zend_Acl_Resource(RESOURCE_ADVT))

                        // Guest may only view content
                        ->allow(ROLE_WORK, array(RESOURCE_WORK))
                        ->allow(ROLE_ADVT, array(RESOURCE_ADVT))
                        ->allow(ROLE_POWER_USER, null, null); // unrestricted access
        }

        Zend_Registry::set('site_acl', $this->_acl);
    }

    private function initAuthAdapter() {
        $this->_authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('db'));
        $this->_authAdapter->setTableName($this->_users_table);
        $this->_authAdapter->setIdentityColumn($this->_Identity);
        $this->_authAdapter->setCredentialColumn($this->_Credential);
        $select = $this->_authAdapter->getDbSelect();
        $select->where("active = '1'");
//       		Zend_Registry::set('authAdapter', $this->_authAdapter);
    }

    private function initAuth() {
        $this->_auth = Zend_Auth::getInstance();
         $this->_auth->setStorage(new Zend_Auth_Storage_Session('frontend'), 'site_user');         
        //$this->_user = (object)array('$this->_Identity' => ROLE_GUEST, 'role' => ROLE_GUEST);
//			Zend_Registry::set('auth', $this->_auth);
    }

        /**
     * Проверка, вошел ли юзер, если нет - редирект на авторизацию
     *
     */

    public function checkUser() {
        if (!SiteAuth::getInstance()->getIdentity()){
            return false;
        }
        return true;
    }
}
